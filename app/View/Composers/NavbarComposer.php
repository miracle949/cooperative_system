<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Otherinfo_tbl;
use App\Models\Membergovern_ids_tbl;
use App\Models\savings_account_tbl;
use App\Models\savings_transaction_tbl;
use App\Models\TimeDeposit;
use Carbon\Carbon;

class NavbarComposer
{
    public function compose(View $view)
    {
        if (!Auth::check()) {
            $view->with([
                'navMissingCount' => 0,
                'navNotifications' => collect(),
            ]);
            return;
        }

        $userId = Auth::id();

        $otherinfo = Otherinfo_tbl::where('user_id', $userId)->first();
        $membergovernIds = Membergovern_ids_tbl::where('user_id', $userId)->first();

        $navMissingCount = 0;
        if (empty($otherinfo->contact_no))
            $navMissingCount++;
        if (empty($otherinfo->present_address))
            $navMissingCount++;
        if (empty($otherinfo->permanent_address))
            $navMissingCount++;
        if (empty($otherinfo->date_of_birth))
            $navMissingCount++;
        if (empty($otherinfo->place_of_birth))
            $navMissingCount++;
        if (empty($otherinfo->sex))
            $navMissingCount++;
        if (empty($otherinfo->civil_status))
            $navMissingCount++;
        if (empty($otherinfo->citizenship))
            $navMissingCount++;
        if (empty($otherinfo->blood_type))
            $navMissingCount++;
        if (empty($otherinfo->height))
            $navMissingCount++;
        if (empty($otherinfo->weight))
            $navMissingCount++;
        if (empty($membergovernIds->sss_id))
            $navMissingCount++;
        if (empty($membergovernIds->philhealth_id))
            $navMissingCount++;
        if (empty($membergovernIds->pagibig_id))
            $navMissingCount++;
        if (empty($membergovernIds->tin_id))
            $navMissingCount++;

        $view->with([
            'navMissingCount' => $navMissingCount,
            'navNotifications' => $this->buildMemberNotifications($userId),
        ]);
    }

    private function buildMemberNotifications($userId)
    {
        $notifications = collect();
        $today = Carbon::today();

        // ── 1) Loan due dates (per-installment, from lending_status_tbls) ──────────
        $typeMap = [
            'Personal Lending' => 'Personal Loan',
            'Emergency Lending' => 'Emergency Loan',
            'Business Lending' => 'Business Loan',
            'Education Lending' => 'Education Loan',
        ];

        $approvedLoans = DB::table('lending_program_tbls as l')
            ->leftJoin('lending_status_tbls as s', 's.lending_id', '=', 'l.id')
            ->where('l.user_id', $userId)
            ->where('l.status', 'Approved')
            ->whereNotNull('s.due_date')
            ->where('s.remaining_balance', '>', 0)
            ->select('l.*', 's.due_date', 's.remaining_balance', 's.payments_made', 's.total_payments')
            ->get();

        foreach ($approvedLoans as $loan) {
            $due = Carbon::parse($loan->due_date);
            $daysLeft = (int) $today->diffInDays($due, false);
            $displayType = $typeMap[$loan->lending_type] ?? $loan->lending_type;
            $installmentNo = ((int) $loan->payments_made) + 1;

            if ($daysLeft < 0) {
                $notifications->push([
                    'icon' => 'fa-triangle-exclamation',
                    'color' => 'red',
                    'title' => 'Loan Payment Overdue',
                    'message' => "{$displayType} — Installment #{$installmentNo} was due on {$due->format('M d, Y')} ("
                        . abs($daysLeft) . " day" . (abs($daysLeft) === 1 ? '' : 's') . " overdue). Please settle to avoid additional late fees.",
                    'time' => $due->diffForHumans(),
                    'sort_at' => $due,
                ]);
            } elseif ($daysLeft <= 7) {
                $notifications->push([
                    'icon' => 'fa-calendar-day',
                    'color' => 'gold',
                    'title' => 'Loan Payment Due Soon',
                    'message' => "{$displayType} — Installment #{$installmentNo} is due on {$due->format('M d, Y')}"
                        . ($daysLeft === 0 ? ' (today)' : " (in {$daysLeft} day" . ($daysLeft === 1 ? '' : 's') . ")") . ".",
                    'time' => $due->diffForHumans(),
                    'sort_at' => $due,
                ]);
            }
        }

        $scAccount = DB::table('share_capital_account_tbls')->where('user_id', $userId)->first();

        if ($scAccount) {
            $paidUp = DB::table('share_capital_transaction_tbls')
                ->where('share_capital_account_id', $scAccount->id)
                ->whereIn('type', ['Deposit', 'Subscription', \App\Http\Controllers\ShareCapital::CONVERSION_TYPE])
                ->whereIn('status', ['Completed', 'completed'])
                ->sum('total_amount') ?? 0;

            $targetAmount = 10000;
            $deadline = Carbon::parse($scAccount->created_at)->addYears(2);
            $daysLeft = (int) $today->diffInDays($deadline, false);

            if ($paidUp < $targetAmount && $daysLeft <= 90) {
                $remaining = $targetAmount - $paidUp;

                $notifications->push([
                    'icon' => 'fa-layer-group',
                    'color' => $daysLeft < 0 ? 'red' : 'gold',
                    'title' => $daysLeft < 0 ? 'Share Capital Subscription Overdue' : 'Share Capital Deadline Approaching',
                    'message' => $daysLeft < 0
                        ? "Your 2-year window to complete your ₱" . number_format($targetAmount, 2) . " share capital subscription ended on {$deadline->format('M d, Y')}. ₱" . number_format($remaining, 2) . " remains unpaid."
                        : "You have ₱" . number_format($remaining, 2) . " remaining to complete your ₱" . number_format($targetAmount, 2) . " share capital subscription by {$deadline->format('M d, Y')} ({$daysLeft} day" . ($daysLeft === 1 ? '' : 's') . " left).",
                    'time' => $deadline->diffForHumans(),
                    'sort_at' => $today,
                ]);
            }
        }

        $savingsAccount = savings_account_tbl::where('user_id', $userId)->first();

        if ($savingsAccount) {
            $patronageTxs = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
                ->where('type', 'deposit')
                ->where('reference_no', 'like', 'PAT-%')
                ->where('transaction_date', '>=', $today->copy()->subDays(30))
                ->get();

            foreach ($patronageTxs as $tx) {
                $notifications->push([
                    'icon' => 'fa-piggy-bank',
                    'color' => 'mint',
                    'title' => 'Patronage Refund Credited',
                    'message' => "₱" . number_format($tx->amount, 2) . " patronage refund has been credited to your Savings account (Ref: {$tx->reference_no}).",
                    'time' => Carbon::parse($tx->created_at ?? $tx->transaction_date)->diffForHumans(),
                    'sort_at' => Carbon::parse($tx->created_at ?? $tx->transaction_date),
                ]);
            }

            $activeTd = TimeDeposit::where('savings_account_id', $savingsAccount->id)
                ->where('status', 'active')
                ->latest('opened_at')
                ->first();

            if ($activeTd && $activeTd->maturity_date) {
                $maturity = Carbon::parse($activeTd->maturity_date);
                $daysLeft = (int) $today->diffInDays($maturity, false);

                if ($maturity->lte($today)) {
                    $notifications->push([
                        'icon' => 'fa-circle-check',
                        'color' => 'mint',
                        'title' => 'Time Deposit Matured',
                        'message' => "Your Time Deposit matured on {$maturity->format('M d, Y')}. Claim it to move your balance + interest back to Regular Savings.",
                        'time' => $maturity->diffForHumans(),
                        'sort_at' => $maturity,
                    ]);
                } elseif ($daysLeft <= 30) {
                    $notifications->push([
                        'icon' => 'fa-calendar-days',
                        'color' => 'gold',
                        'title' => 'Time Deposit Maturing Soon',
                        'message' => "Your Time Deposit matures on {$maturity->format('M d, Y')} (in {$daysLeft} day" . ($daysLeft === 1 ? '' : 's') . ").",
                        'time' => $maturity->diffForHumans(),
                        'sort_at' => $maturity,
                    ]);
                }
            }
        }

        return $notifications->sortByDesc('sort_at')->values();
    }
}