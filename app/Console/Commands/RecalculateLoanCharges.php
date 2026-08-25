<?php

namespace App\Console\Commands;

use App\Models\lending_program_tbl;
use App\Models\lending_status_tbl;
use App\Models\AuditLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * One-time retroactive fix: folds Processing Fee, Service & Legal Fee,
 * Loan Protection Plan, and Retention/CBU into total_payment /
 * monthly_payment for existing loans, matching the updated formula in
 * lendingController@lendingProgram.
 *
 * Only touches Pending and Approved loans. Completed loans are left alone
 * on purpose — a member who already paid off a loan under the old terms
 * would show as having "underpaid" (or worse, a negative balance) if we
 * rewrote their total after the fact.
 *
 * Usage:
 *   php artisan loans:recalculate-charges --dry-run   (preview only, no writes)
 *   php artisan loans:recalculate-charges              (actually applies changes)
 */
class RecalculateLoanCharges extends Command
{
    protected $signature = 'loans:recalculate-charges {--dry-run : Preview changes without saving}';
    protected $description = 'Retroactively fold loan charges into total_payment/monthly_payment for existing Pending/Approved loans';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $loans = lending_program_tbl::whereIn('status', ['Pending', 'Approved'])->get();

        if ($loans->isEmpty()) {
            $this->info('No Pending/Approved loans found. Nothing to do.');
            return self::SUCCESS;
        }

        $this->info(($dryRun ? '[DRY RUN] ' : '') . "Found {$loans->count()} loan(s) to check.");
        $this->newLine();

        $headers = ['ID', 'Ref No.', 'Status', 'Old Total', 'New Total', 'Old Monthly', 'New Monthly', 'Δ Balance'];
        $rows = [];
        $updated = 0;

        foreach ($loans as $loan) {
            $settings = DB::table('loan_settings_tbls')
                ->where('loan_type', $loan->lending_type)
                ->where('is_active', true)
                ->first();

            if (!$settings) {
                $this->warn("Skipping loan #{$loan->id} ({$loan->reference_no}) — no active settings found for type '{$loan->lending_type}'.");
                continue;
            }

            $principal = (float) $loan->lending_amount;
            $termMonths = (int) filter_var($loan->lending_type_term, FILTER_SANITIZE_NUMBER_INT);

            if ($termMonths <= 0 || $principal <= 0) {
                $this->warn("Skipping loan #{$loan->id} ({$loan->reference_no}) — invalid principal/term.");
                continue;
            }

            // ── Recompute fees exactly as lendingProgram() does ──────────────
            $processingFee = round($principal * ($settings->processing_fee_rate / 100), 2);
            $serviceFee = round($principal * ($settings->service_fee_rate / 100), 2);
            $loanProtectionFee = round($settings->loan_protection_fee * $termMonths, 2);
            $retentionRateApplied = $settings->retention_unpaid_rate;
            $retentionAmount = round($principal * ($retentionRateApplied / 100), 2);

            // Interest — diminishing balance, same loop as original
            $monthlyRate = $settings->interest_rate / 100;
            $principalPerMonth = $principal / $termMonths;
            $totalInterest = 0;
            for ($i = 0; $i < $termMonths; $i++) {
                $remaining = $principal - ($principalPerMonth * $i);
                $totalInterest += $remaining * $monthlyRate;
            }
            $totalInterest = round($totalInterest, 2);

            $totalCharges = $processingFee + $serviceFee + $loanProtectionFee + $retentionAmount;
            $newTotalPayment = round($principal + $totalInterest + $totalCharges, 2);
            $newMonthlyPayment = round($newTotalPayment / $termMonths, 2);

            $oldTotalPayment = (float) ($loan->total_payment ?? $principal);
            $oldMonthlyPayment = (float) ($loan->monthly_payment ?? 0);

            // Nothing changed (e.g. this loan was already applied for under
            // the new formula) — skip it, no need to touch the row.
            if (abs($newTotalPayment - $oldTotalPayment) < 0.01) {
                continue;
            }

            $balanceDelta = round($newTotalPayment - $oldTotalPayment, 2);

            $rows[] = [
                $loan->id,
                $loan->reference_no,
                $loan->status,
                number_format($oldTotalPayment, 2),
                number_format($newTotalPayment, 2),
                number_format($oldMonthlyPayment, 2),
                number_format($newMonthlyPayment, 2),
                '+' . number_format($balanceDelta, 2),
            ];

            if (!$dryRun) {
                DB::transaction(function () use ($loan, $newTotalPayment, $newMonthlyPayment, $balanceDelta) {
                    $loan->total_payment = $newTotalPayment;
                    $loan->monthly_payment = $newMonthlyPayment;
                    $loan->save();

                    // Push the same delta onto the live remaining balance so
                    // the member's outstanding amount reflects the corrected
                    // total. Payments already made are untouched — only the
                    // balance still owed changes.
                    $status = lending_status_tbl::where('lending_id', $loan->id)->first();
                    if ($status) {
                        $status->remaining_balance = max(0, (float) $status->remaining_balance + $balanceDelta);
                        $status->save();
                    }

                    AuditLog::log(
                        'Loan Charges Recalculated',
                        "Retroactively folded charges into total_payment for loan #{$loan->id} (Ref: {$loan->reference_no}). "
                        . "Balance adjusted by +₱{$balanceDelta}.",
                        'loan',
                        $loan->id
                    );
                });
            }

            $updated++;
        }

        if (empty($rows)) {
            $this->info('All checked loans already match the new formula. Nothing to update.');
            return self::SUCCESS;
        }

        $this->table($headers, $rows);
        $this->newLine();

        if ($dryRun) {
            $this->comment("DRY RUN — {$updated} loan(s) would be updated. Re-run without --dry-run to apply.");
        } else {
            $this->info("Done — {$updated} loan(s) updated. Each change was logged to AuditLog.");
        }

        return self::SUCCESS;
    }
}