<?php

namespace App\Console\Commands;

use App\Models\savings_account_tbl;
use App\Models\savings_transaction_tbl;
use App\Models\Savings_settings_tbl;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessSavingsInterestAndMaturities extends Command
{
    /**
     * php artisan savings:process
     *
     * Run this quarterly (Jan/Apr/Jul/Oct 1st) via the scheduler.
     * It does two independent things:
     *   1. Credits quarterly interest on every account's Regular Savings (`balance`)
     *      into `interest_accrued_balance` (a separate bucket, per your design).
     *   2. Releases any Time Deposit that has reached its maturity date —
     *      principal + interest go back into `balance`, td_* fields reset.
     */
    protected $signature = 'savings:process';

    protected $description = 'Credit quarterly interest on Regular Savings and release matured Time Deposits';

    public function handle(): int
    {
        $this->creditQuarterlyInterest();
        $this->releaseMaturedTimeDeposits();

        $this->info('Savings interest + maturity processing complete.');
        return self::SUCCESS;
    }

    /**
     * Credit quarterly interest on Regular Savings balance for every account.
     * Rate is pulled live from savings_settings_tbls (Regular Savings row) —
     * NOT snapshotted, since Regular Savings interest can change over time.
     */
    private function creditQuarterlyInterest(): void
    {
        $rate = Savings_settings_tbl::where('savings_type', 'Regular Savings')->value('interest_rate') ?? 4.00;
        $quarterlyRate = $rate / 100 / 4; // annual % → quarterly fraction

        $accounts = savings_account_tbl::where('balance', '>', 0)->get();

        foreach ($accounts as $account) {
            // Skip if already credited this quarter (avoids double-crediting on re-run)
            if (
                $account->last_interest_credited_at
                && Carbon::parse($account->last_interest_credited_at)->isSameQuarter(Carbon::today())
            ) {
                continue;
            }

            $interest = round($account->balance * $quarterlyRate, 2);

            if ($interest <= 0) {
                continue;
            }

            DB::transaction(function () use ($account, $interest) {
                $account->update([
                    'interest_accrued_balance' => $account->interest_accrued_balance + $interest,
                    'last_interest_credited_at' => Carbon::today(),
                ]);

                savings_transaction_tbl::create([
                    'savings_account_id' => $account->id,
                    'type' => 'interest',
                    'amount' => $interest,
                    'payment_method' => 'System',
                    'balance_after' => $account->balance, // Regular Savings balance itself is unchanged
                    'note' => 'Quarterly interest credited',
                    'reference_no' => 'INT-' . strtoupper(bin2hex(random_bytes(3))) . '-' . Carbon::today()->format('Ymd'),
                    'transaction_date' => Carbon::today(),
                ]);
            });

            $this->line("Credited ₱{$interest} interest to account #{$account->id}");
        }
    }

    /**
     * Release any Time Deposit that has reached (or passed) its maturity date.
     * Principal + interest for the full term go back into Regular Savings.
     */
    private function releaseMaturedTimeDeposits(): void
    {
        $matured = savings_account_tbl::where('td_status', 'active')
            ->whereDate('td_maturity_date', '<=', Carbon::today())
            ->get();

        foreach ($matured as $account) {
            $principal = (float) $account->td_balance;
            $rate = (float) $account->td_interest_rate;
            $termMonths = (int) $account->td_term_months;

            // Simple interest for the full term: principal × rate × (term/12)
            $interest = round($principal * ($rate / 100) * ($termMonths / 12), 2);
            $totalPayout = $principal + $interest;
            $newBalance = $account->balance + $totalPayout;
            $referenceNo = 'TDR-' . strtoupper(bin2hex(random_bytes(3))) . '-' . Carbon::today()->format('Ymd');

            DB::transaction(function () use ($account, $newBalance, $totalPayout, $termMonths, $rate, $referenceNo) {
                $account->update([
                    'balance' => $newBalance,
                    'td_balance' => 0.00,
                    'td_status' => 'matured',
                    'td_reference_no' => $referenceNo,
                ]);

                savings_transaction_tbl::create([
                    'savings_account_id' => $account->id,
                    'type' => 'td_release',
                    'amount' => $totalPayout,
                    'payment_method' => 'Internal Transfer',
                    'balance_after' => $newBalance,
                    'note' => "Time Deposit matured ({$termMonths}mo @ {$rate}% p.a.) — principal + interest released",
                    'reference_no' => $referenceNo,
                    'transaction_date' => Carbon::today(),
                ]);
            });

            AuditLog::log(
                'Time Deposit Matured',
                "Released ₱{$totalPayout} (principal + interest) for account #{$account->id} (Ref: {$referenceNo})",
                'savings',
                $account->id
            );

            $this->line("Released ₱{$totalPayout} matured Time Deposit for account #{$account->id}");
        }
    }
}