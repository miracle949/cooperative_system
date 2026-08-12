<?php

namespace App\Services\PatronageSources;

use App\Models\DividendSetting;
use Illuminate\Support\Facades\DB;

class LoanRepaymentPatronageSource implements PatronageSource
{
    public function getName(): string
    {
        return 'Loan Repayments';
    }

    /**
     * Patronage from loan repayments is based on income components only:
     *   interest_paid + service_fee_paid + COALESCE(late_fee, 0)
     *
     * The patronage_basis setting controls whether late fees are included:
     *   - 'total_repayment': includes late fees (income components + late_fee)
     *   - 'net_repayment': excludes late fees (income components only)
     *
     * Fallback: if all income columns are zero (old records before migration),
     * falls back to amount_paid for backward compatibility.
     */
    public function getPatronageForYear(int $userId, int $year): float
    {
        $basis = $this->getPatronageBasis($year);

        $query = DB::table('lending_repayments_tbls')
            ->where('user_id', $userId)
            ->whereYear('payment_date', $year);

        if ($basis === 'net_repayment') {
            return (float) $query->sum(DB::raw(
                'CASE WHEN (interest_paid + service_fee_paid) > 0 '
                . 'THEN (interest_paid + service_fee_paid) '
                . 'ELSE amount_paid END'
            ));
        }

        // Default: total_repayment (includes late fees)
        return (float) $query->sum(DB::raw(
            'CASE WHEN (interest_paid + service_fee_paid) > 0 '
            . 'THEN (interest_paid + service_fee_paid + COALESCE(late_fee, 0)) '
            . 'ELSE amount_paid END'
        ));
    }

    public function getTotalPatronageForYear(int $year): float
    {
        return (float) $this->getAllPatronageForYear($year)
            ->reduce(fn ($carry, $amount) => $carry + $amount, 0);
    }

    public function getAllPatronageForYear(int $year): array
    {
        $basis = $this->getPatronageBasis($year);

        $incomeExpr = $basis === 'net_repayment'
            ? 'CASE WHEN (interest_paid + service_fee_paid) > 0 THEN (interest_paid + service_fee_paid) ELSE amount_paid END'
            : 'CASE WHEN (interest_paid + service_fee_paid) > 0 THEN (interest_paid + service_fee_paid + COALESCE(late_fee, 0)) ELSE amount_paid END';

        $results = DB::table('lending_repayments_tbls')
            ->whereYear('payment_date', $year)
            ->select('user_id')
            ->selectRaw("user_id, SUM({$incomeExpr}) as total")
            ->groupBy('user_id')
            ->get();

        $patronage = [];
        foreach ($results as $row) {
            $patronage[$row->user_id] = (float) $row->total;
        }

        return $patronage;
    }

    private function getPatronageBasis(int $year): string
    {
        $setting = DividendSetting::where('year', $year)->first();
        return $setting->patronage_basis ?? 'total_repayment';
    }
}
