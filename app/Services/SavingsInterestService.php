<?php

namespace App\Services;

use App\Models\savings_account_tbl;
use App\Models\savings_transaction_tbl;
use App\Models\Savings_settings_tbl;
use App\Models\AuditLog;
use Carbon\Carbon;

class SavingsInterestService
{
    /**
     * Average daily balance over [start, end], inclusive.
     * Walks day-by-day using balance_after snapshots from savings_transaction_tbl.
     */
    public function averageDailyBalance(savings_account_tbl $account, Carbon $start, Carbon $end): float
    {
        $days = $start->diffInDays($end) + 1;

        // Balance carried in from before the quarter started
        $priorTx = savings_transaction_tbl::where('savings_account_id', $account->id)
            ->where('transaction_date', '<', $start->toDateString())
            ->whereNotNull('balance_after')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->first();

        $runningBalance = $priorTx ? (float) $priorTx->balance_after : 0.0;

        $txsInQuarter = savings_transaction_tbl::where('savings_account_id', $account->id)
            ->whereBetween('transaction_date', [$start->toDateString(), $end->toDateString()])
            ->whereNotNull('balance_after')
            ->orderBy('transaction_date')
            ->orderBy('created_at')
            ->get()
            ->groupBy(fn($tx) => Carbon::parse($tx->transaction_date)->toDateString());

        $weightedSum = 0.0;
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            $key = $cursor->toDateString();
            if (isset($txsInQuarter[$key])) {
                // Use balance after the LAST transaction of that day
                $runningBalance = (float) $txsInQuarter[$key]->last()->balance_after;
            }
            $weightedSum += $runningBalance;
            $cursor->addDay();
        }

        return $days > 0 ? $weightedSum / $days : 0.0;
    }

    /**
     * Credit interest for one account for a given quarter.
     * Returns details if credited, null if skipped (already credited / zero interest).
     */
    public function creditQuarterlyInterest(
        savings_account_tbl $account,
        Carbon $quarterStart,
        Carbon $quarterEnd,
        bool $force = false
    ): ?array {
        if (
            !$force
            && $account->interest_last_credited_at
            && Carbon::parse($account->interest_last_credited_at)->gte($quarterEnd)
        ) {
            return null; // already credited for this quarter or later
        }

        $rate = Savings_settings_tbl::where('savings_type', 'Regular Savings')->value('interest_rate') ?? 4.00;

        $avgBalance = $this->averageDailyBalance($account, $quarterStart, $quarterEnd);
        $days = $quarterStart->diffInDays($quarterEnd) + 1;
        $interest = round($avgBalance * ($rate / 100) * ($days / 365), 2);

        // Always stamp the credited date so we don't reprocess this quarter, even if interest is 0
        if ($interest <= 0) {
            $account->update(['interest_last_credited_at' => $quarterEnd->toDateString()]);
            return null;
        }

        $newInterestBalance = (float) $account->interest_accrued_balance + $interest;
        $referenceNo = 'INT-' . strtoupper(bin2hex(random_bytes(3))) . '-' . $quarterEnd->format('Ymd');

        $account->update([
            'interest_accrued_balance' => $newInterestBalance,
            'interest_last_credited_at' => $quarterEnd->toDateString(),
        ]);

        savings_transaction_tbl::create([
            'savings_account_id' => $account->id,
            'type' => 'interest_credit',
            'amount' => $interest,
            'payment_method' => 'System',
            'balance_after' => $account->balance, // regular savings balance untouched
            'note' => sprintf(
                'Quarterly interest (%.2f%% p.a.) for %s–%s · Avg daily balance ₱%s',
                $rate,
                $quarterStart->format('M d'),
                $quarterEnd->format('M d, Y'),
                number_format($avgBalance, 2)
            ),
            'reference_no' => $referenceNo,
            'transaction_date' => $quarterEnd->toDateString(),
        ]);

        AuditLog::log(
            'Savings Interest Credited',
            "Credited ₱{$interest} interest to savings account #{$account->id} (Ref: {$referenceNo})",
            'savings',
            $account->id
        );

        return [
            'account_id' => $account->id,
            'interest' => $interest,
            'avg_balance' => $avgBalance,
            'reference_no' => $referenceNo,
        ];
    }

    /** Credit interest to every active savings account for the given (or previous) quarter. */
    public function creditForAllAccounts(?Carbon $quarterStart = null, ?Carbon $quarterEnd = null, bool $force = false): array
    {
        if (!$quarterStart || !$quarterEnd) {
            [$quarterStart, $quarterEnd] = $this->previousQuarterRange();
        }

        $results = [];

        savings_account_tbl::where('status', 'active')
            ->chunkById(100, function ($accounts) use (&$results, $quarterStart, $quarterEnd, $force) {
                foreach ($accounts as $account) {
                    $result = $this->creditQuarterlyInterest($account, $quarterStart, $quarterEnd, $force);
                    if ($result) {
                        $results[] = $result;
                    }
                }
            });

        return $results;
    }

    /** [start, end] of the quarter that most recently ended. */
    public function previousQuarterRange(): array
    {
        $now = Carbon::now();
        $currentQuarterStartMonth = (intdiv($now->month - 1, 3) * 3) + 1;
        $currentQuarterStart = Carbon::create($now->year, $currentQuarterStartMonth, 1)->startOfDay();

        $previousQuarterEnd = $currentQuarterStart->copy()->subDay()->endOfDay();
        $previousQuarterStart = $previousQuarterEnd->copy()->startOfMonth()->subMonths(2)->startOfMonth();

        return [$previousQuarterStart, $previousQuarterEnd];
    }
}