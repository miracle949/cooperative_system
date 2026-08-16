<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\savings_account_tbl;
use App\Models\savings_transaction_tbl;
use App\Models\share_capital_account_tbl;
use App\Models\share_capital_transaction_tbl;
use App\Models\TimeDeposit;
use App\Models\Users_tbl;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class SavingsController extends Controller
{
    /**
     * Generate a unique reference number.
     * Format: SAV-DEP-20260326-A3F9 or SAV-WDR-20260326-B7K2
     */
    private function generateReferenceNo(string $type): string
    {
        $prefix = $type === 'deposit' ? 'SAV-DEP' : 'SAV-WDR';
        $date = Carbon::today()->format('Ymd');

        do {
            $random = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
            $refNo = "{$prefix}-{$date}-{$random}";
        } while (savings_transaction_tbl::where('reference_no', $refNo)->exists());

        return $refNo;
    }

    /**
     * Show the savings page.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;

        // ★ NEW: search/date filters for transaction history
        $ref = trim((string) $request->query('ref', ''));
        $date = $request->query('date', '');
        $status = strtolower(trim((string) $request->query('status', 'all')));

        // ★ NEW: growth chart year selector
        $growthYear = (int) $request->query('growth_year', Carbon::now()->year);

        // Get or create savings account
        $savingsAccount = savings_account_tbl::where('user_id', $user->id)->first();

        if (!$savingsAccount) {
            $savingsAccount = savings_account_tbl::create([
                'user_id' => $user->id,
                'balance' => 0.00,
                'status' => 'active',
                'opened_at' => Carbon::today(),
            ]);
        }

        // ── Savings Breakdown: three independent buckets that sum to Total Savings Balance ──
        $activeTd = TimeDeposit::where('savings_account_id', $savingsAccount->id)
            ->where('status', 'active')
            ->latest('opened_at')
            ->first();

        $regularSavingsBalance = (float) $savingsAccount->balance;
        $timeDepositBalance = (float) ($activeTd->balance ?? 0);
        $interestAccruedBalance = (float) ($activeTd->interest_accrued_balance ?? 0);

        $totalSavingsBalance = $regularSavingsBalance + $timeDepositBalance + $interestAccruedBalance;

        $regularSavingsPercent = $totalSavingsBalance > 0
            ? round(($regularSavingsBalance / $totalSavingsBalance) * 100, 1)
            : 0;
        $timeDepositPercent = $totalSavingsBalance > 0
            ? round(($timeDepositBalance / $totalSavingsBalance) * 100, 1)
            : 0;
        $interestAccruedPercent = $totalSavingsBalance > 0
            ? round(($interestAccruedBalance / $totalSavingsBalance) * 100, 1)
            : 0;

        // Regular Savings rate + crediting frequency — pulled live from settings (not locked in, can change any time)
        $regularSavingsSetting = \App\Models\Savings_settings_tbl::where('savings_type', 'Regular Savings')->first();
        $regularSavingsRate = $regularSavingsSetting->interest_rate ?? 4.00;
        $regularSavingsFrequency = $regularSavingsSetting->crediting_frequency ?? 'Monthly';

        // Estimated interest accrued this quarter, prorated by days elapsed.
        // Actual crediting happens via the scheduled SavingsInterestService job.
        $quarterStartMonth = (intdiv(Carbon::now()->month - 1, 3)) * 3 + 1;
        $quarterStart = Carbon::create(Carbon::now()->year, $quarterStartMonth, 1)->startOfDay();
        $daysElapsedInQuarter = $quarterStart->diffInDays(Carbon::now()) + 1;

        $estimatedQuarterInterest = round(
            $regularSavingsBalance * ($regularSavingsRate / 100) * ($daysElapsedInQuarter / 365),
            2
        );


        // Time Deposit display info — only meaningful once a TD is actually opened
        $hasActiveTimeDeposit = $activeTd && (float) ($activeTd->goal_amount ?? 0) > 0;
        $timeDepositRate = $activeTd->interest_rate ?? null;
        $timeDepositMaturity = $activeTd && $activeTd->maturity_date
            ? Carbon::parse($activeTd->maturity_date)->format('M d, Y')
            : null;

        $tdMaturityDate = $activeTd && $activeTd->maturity_date ? Carbon::parse($activeTd->maturity_date) : null;
        $tdMatured = $hasActiveTimeDeposit && $tdMaturityDate && $tdMaturityDate->lte(Carbon::today());

        // List of years the member actually has transactions in (always includes current year)
        $availableGrowthYears = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
            ->selectRaw('DISTINCT YEAR(transaction_date) as yr')
            ->pluck('yr')
            ->push(Carbon::now()->year)
            ->unique()
            ->sortDesc()
            ->values();

        $isCurrentYear = $growthYear === Carbon::now()->year;

        if ($isCurrentYear) {
            // Rolling last 6 months ending this month
            $growthStart = Carbon::now()->startOfMonth()->subMonths(5);
            $growthMonths = collect(range(5, 0))->map(fn($i) => Carbon::now()->subMonths($i));
        } else {
            // Full calendar year Jan–Dec of the selected year
            $growthStart = Carbon::createFromDate($growthYear, 1, 1)->startOfMonth();
            $growthMonths = collect(range(0, 11))->map(fn($i) => Carbon::createFromDate($growthYear, 1, 1)->addMonths($i));
        }

        $growthTxs = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
            ->where('transaction_date', '>=', $growthStart)
            ->when(!$isCurrentYear, fn($q) => $q->whereYear('transaction_date', $growthYear))
            ->whereIn('type', ['deposit', 'withdrawal'])
            ->get()
            ->groupBy(fn($tx) => Carbon::parse($tx->transaction_date)->format('Y-m'));

        $savingsGrowth = collect();
        foreach ($growthMonths as $month) {
            $key = $month->format('Y-m');
            $monthTxs = $growthTxs->get($key, collect());

            $net = $monthTxs->sum(fn($tx) => $tx->type === 'deposit' ? (float) $tx->amount : -(float) $tx->amount);

            $savingsGrowth->push([
                'label' => $month->format('M'),
                'net' => $net,
                'is_current' => $month->isSameMonth(Carbon::now()),
            ]);
        }

        $maxGrowth = $savingsGrowth->max(fn($m) => max($m['net'], 0)) ?: 1;

        $savingsGrowth = $savingsGrowth->map(function ($m) use ($maxGrowth) {
            $m['height_percent'] = $m['net'] > 0
                ? max(6, round(($m['net'] / $maxGrowth) * 78))
                : 4;
            return $m;
        });

        $tdHistory = TimeDeposit::where('savings_account_id', $savingsAccount->id)
            ->orderByRaw("CASE WHEN status = 'claimed' THEN 1 ELSE 0 END")
            ->orderBy('opened_at')
            ->orderBy('created_at')
            ->get()
            ->map(function ($td) {
                $isMatured = Carbon::parse($td->maturity_date)->lte(Carbon::today());
                $isFullyFunded = (float) $td->balance >= (float) $td->goal_amount && (float) $td->goal_amount > 0;

                if ($td->status === 'claimed') {
                    $td->display_status = 'completed';
                } elseif ($td->status === 'active' && $isMatured) {
                    $td->display_status = 'matured';
                } elseif ($td->status === 'active' && $isFullyFunded) {
                    $td->display_status = 'goal_reached';
                } else {
                    $td->display_status = 'in_progress';
                }

                $td->display_balance = $td->status === 'claimed'
                    ? (float) ($td->claimed_amount ?? 0)
                    : (float) $td->balance;
                return $td;
            });

        $hasShareCapital = \Illuminate\Support\Facades\DB::table('share_capital_account_tbls')
            ->where('user_id', $user->id)
            ->where('status', 'Active')
            ->where('total_shares', '>', 0)
            ->exists();

        // if (!$savingsAccount) {
        //     $savingsAccount = savings_account_tbl::create([
        //         'user_id' => $user->id,
        //         'balance' => 0.00,
        //         'status' => 'active',
        //         'opened_at' => Carbon::today(),
        //     ]);
        // }

        // ★ NEW: type filter — all / deposit / withdrawal / interest_credit
        $type = $request->query('type', 'all');

        $transactionsQuery = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
            ->whereIn('type', ['deposit', 'withdrawal', 'td_release', ShareCapital::CONVERSION_TYPE]) // ★ CHANGED: td_release (TD claims) and savings→share capital conversions shown here too
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc');

        if (in_array($type, ['deposit', 'withdrawal', 'td_release'])) {
            $transactionsQuery->where('type', $type);
        }

        // ★ NEW: filter by reference no.
        if ($ref !== '') {
            $transactionsQuery->where('reference_no', 'like', '%' . $ref . '%');
        }

        // ★ NEW: filter by specific date
        if ($status !== 'all') {
            $transactionsQuery->where('status', $status);
        }

        // ★ NEW: 10 per page, keeps ?type=/?ref=/?date= on every pagination link automatically
        $transactions = $transactionsQuery->paginate(10)->withQueryString();

        $totalMonths = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
            ->groupByRaw("DATE_FORMAT(transaction_date, '%Y-%m')")
            ->count();

        $monthlyAverage = $totalMonths > 0
            ? $savingsAccount->balance / $totalMonths
            : 0;

        $lastUpdated = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
            ->orderBy('transaction_date', 'desc')
            ->value('transaction_date');

        $lastUpdated = $lastUpdated
            ? Carbon::parse($lastUpdated)->diffForHumans()
            : 'No transactions yet';

        $monthsActive = (int) ceil(
            Carbon::parse($savingsAccount->opened_at)->floatDiffInMonths(Carbon::today())
        );

        $availableStatuses = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
            ->whereNotNull('status')
            ->pluck('status')
            ->map(fn($s) => ucfirst($s))
            ->unique()
            ->sortBy(fn($s) => strtolower($s))
            ->values();

        // The QR the admin uploaded in Settings → Payment Methods Management
        $gcashPaymentMethod = \App\Models\PaymentMethod::where('method_name', 'GCash')
            ->where('is_active', true)
            ->first();

        return view(
            'members_components.savings',
            [
                "username" => $username,
                "email" => $email
            ],
            compact(
                'savingsAccount',
                'transactions',
                'type',
                'ref',
                'date',
                'status',              // ← ADD THIS
                'availableStatuses',   // ← ADD THIS
                'growthYear',
                'availableGrowthYears',
                'totalMonths',
                'monthlyAverage',
                'lastUpdated',
                'monthsActive',
                'hasShareCapital',
                'regularSavingsBalance',
                'timeDepositBalance',
                'interestAccruedBalance',
                'totalSavingsBalance',
                'regularSavingsPercent',
                'timeDepositPercent',
                'interestAccruedPercent',
                'regularSavingsRate',
                'regularSavingsFrequency',
                'hasActiveTimeDeposit',
                'timeDepositRate',
                'timeDepositMaturity',
                'tdMatured',
                'tdHistory',
                'savingsGrowth',
                'estimatedQuarterInterest',
                'gcashPaymentMethod'
            )
        );
    }

    public function TimeDeposit()
    {
        $user = Auth::user();
        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;

        // ★ NEW: search/date/status filters for TD transaction history
        $tdRef = trim((string) request()->query('td_ref', ''));
        $tdDate = request()->query('td_date', '');
        $tdStatus = strtolower(trim((string) request()->query('td_status', 'all')));

        $savingsAccount = savings_account_tbl::where('user_id', $user->id)->first();

        if (!$savingsAccount) {
            $savingsAccount = savings_account_tbl::create([
                'user_id' => $user->id,
                'balance' => 0.00,
                'status' => 'active',
                'opened_at' => Carbon::today(),
            ]);
        }

        $activeTd = TimeDeposit::where('savings_account_id', $savingsAccount->id)
            ->where('status', 'active')
            ->latest('opened_at')
            ->first();

        $regularSavingsBalance = (float) $savingsAccount->balance;
        $tdBalance = (float) ($activeTd->balance ?? 0);
        $tdGoalAmount = (float) ($activeTd->goal_amount ?? 0);
        $tdRemaining = max(0, $tdGoalAmount - $tdBalance);
        $hasActiveTimeDeposit = (bool) $activeTd && $tdGoalAmount > 0;
        $tdRate = (float) ($activeTd->interest_rate ?? 0);
        $tdTermMonths = (int) ($activeTd->term_months ?? 0);
        $tdOpenedAt = $activeTd && $activeTd->opened_at ? Carbon::parse($activeTd->opened_at) : null;
        $tdMaturityDate = $activeTd && $activeTd->maturity_date ? Carbon::parse($activeTd->maturity_date) : null;
        $tdReferenceNo = $activeTd->reference_no ?? null;

        $tdMatured = $hasActiveTimeDeposit && $tdMaturityDate && $tdMaturityDate->lte(Carbon::today());

        // Goal progress — % of the TARGET amount that's been deposited so far
        $goalProgressPercent = $tdGoalAmount > 0
            ? min(100, (int) round(($tdBalance / $tdGoalAmount) * 100))
            : 0;

        $goalReached = $tdGoalAmount > 0 && $tdBalance >= $tdGoalAmount;

        // Time-based stats (days to maturity, interest projection) — still tracked separately from goal progress
        $daysToGo = 0;
        $interestEarnedSoFar = 0.0;
        $fullTermInterest = 0.0;
        $projectedMaturityValue = 0.0;

        if ($hasActiveTimeDeposit && $tdOpenedAt && $tdMaturityDate) {
            $totalTermDays = max(1, $tdOpenedAt->diffInDays($tdMaturityDate));
            $elapsedDays = min($totalTermDays, $tdOpenedAt->diffInDays(Carbon::today()));

            $daysToGo = $tdMatured ? 0 : (int) Carbon::today()->diffInDays($tdMaturityDate);

            $fullTermInterest = round($tdBalance * ($tdRate / 100) * ($tdTermMonths / 12), 2);
            $interestEarnedSoFar = $tdMatured
                ? $fullTermInterest
                : round($fullTermInterest * ($elapsedDays / $totalTermDays), 2);
            $projectedMaturityValue = $tdBalance + $fullTermInterest;
        }

        // ── Time-Deposit-specific transaction history (goal deposits + release/claim) ──
        $tdTransactionsQuery = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
            ->whereIn('type', ['td_open', 'td_lock', 'td_release'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($tdRef !== '') {
            $tdTransactionsQuery->where('reference_no', 'like', '%' . $tdRef . '%');
        }

        if ($tdDate !== '') {
            $tdTransactionsQuery->whereDate('transaction_date', $tdDate);
        }

        if ($tdStatus !== 'all') {
            $tdTransactionsQuery->where('status', $tdStatus);
        }

        $tdTransactions = $tdTransactionsQuery->paginate(10, ['*'], 'td_page')->withQueryString();

        $availableTdStatuses = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
            ->whereIn('type', ['td_open', 'td_lock', 'td_release'])
            ->whereNotNull('status')
            ->pluck('status')
            ->map(fn($s) => ucfirst($s))
            ->unique()
            ->sortBy(fn($s) => strtolower($s))
            ->values();

        // ── Notifications feed ──
        $notifications = collect();

        if ($tdMatured) {
            $notifications->push([
                'icon' => 'fa-circle-check',
                'color' => 'green',
                'title' => 'Time Deposit matured',
                'message' => 'Your Time Deposit matured on ' . $tdMaturityDate->format('M d, Y')
                    . '. Claim it to move your balance + interest back to Regular Savings.',
                'time' => null,
            ]);
        } elseif ($hasActiveTimeDeposit && $goalReached) {
            $notifications->push([
                'icon' => 'fa-bullseye',
                'color' => 'green',
                'title' => 'Goal reached!',
                'message' => 'You\'ve fully funded your ₱' . number_format($tdGoalAmount, 2)
                    . ' goal. It will mature on ' . $tdMaturityDate->format('M d, Y') . '.',
                'time' => null,
            ]);
        } elseif ($hasActiveTimeDeposit && $daysToGo <= 30) {
            $notifications->push([
                'icon' => 'fa-calendar-days',
                'color' => 'gold',
                'title' => 'Maturity coming up',
                'message' => 'Your Time Deposit matures in ' . $daysToGo . ' day' . ($daysToGo === 1 ? '' : 's')
                    . ', on ' . $tdMaturityDate->format('M d, Y') . '.',
                'time' => null,
            ]);
        }

        $recentTdActivity = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
            ->whereIn('type', ['td_open', 'td_lock', 'td_release'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        foreach ($recentTdActivity as $tx) {
            if ($tx->type === 'td_open') {
                $icon = 'fa-bullseye';
                $color = 'blue';
                $title = 'Time Deposit opened';
            } elseif ($tx->type === 'td_lock') {
                $icon = 'fa-piggy-bank';
                $color = 'blue';
                $title = 'Deposited toward goal';
            } else {
                $icon = 'fa-hand-holding-dollar';
                $color = 'green';
                $title = 'Time Deposit claimed';
            }

            $notifications->push([
                'icon' => $icon,
                'color' => $color,
                'title' => $title,
                'message' => $tx->note ?? 'Time Deposit activity.',
                'time' => Carbon::parse($tx->created_at)->diffForHumans(),
            ]);
        }

        return view(
            "members_components.savings_time_deposit",
            [
                "username" => $username,
                "email" => $email,
            ],
            compact(
                'savingsAccount',
                'regularSavingsBalance',
                'tdBalance',
                'tdGoalAmount',
                'tdRemaining',
                'goalProgressPercent',
                'goalReached',
                'hasActiveTimeDeposit',
                'tdRate',
                'tdTermMonths',
                'tdOpenedAt',
                'tdMaturityDate',
                'tdReferenceNo',
                'tdMatured',
                'daysToGo',
                'interestEarnedSoFar',
                'projectedMaturityValue',
                'tdTransactions',
                'tdRef',
                'tdDate',
                'tdStatus',
                'availableTdStatuses',
                'notifications'
            )
        );
    }

    public function adminCreditInterest(Request $request, \App\Services\SavingsInterestService $service)
    {
        $results = $service->creditForAllAccounts(force: (bool) $request->boolean('force'));

        return response()->json([
            'success' => true,
            'message' => 'Credited interest to ' . count($results) . ' account(s).',
            'credited' => $results,
        ]);
    }

    /**
     * Deposit funds directly toward an active Time Deposit's goal.
     * Independent of Regular Savings balance — the member can deposit
     * whatever amount they want (e.g. cash/GCash), same as a Savings deposit.
     */
    public function depositToTimeDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $savingsAccount = savings_account_tbl::where('user_id', $user->id)->firstOrFail();

        $activeTd = TimeDeposit::where('savings_account_id', $savingsAccount->id)
            ->where('status', 'active')
            ->latest('opened_at')
            ->first();

        if (!$activeTd) {
            return back()->withErrors(['td_amount' => "You don't have an active Time Deposit goal to deposit into."]);
        }

        $goal = (float) $activeTd->goal_amount;
        if ($goal <= 0) {
            return back()->withErrors(['td_amount' => 'Your Time Deposit goal is not properly set. Please open a new Time Deposit.']);
        }

        $maturityDate = $activeTd->maturity_date ? Carbon::parse($activeTd->maturity_date) : null;
        if ($maturityDate && $maturityDate->lte(Carbon::today())) {
            return back()->withErrors(['td_amount' => 'This Time Deposit has already matured. Claim it before depositing further.']);
        }

        $current = (float) $activeTd->balance;
        $remaining = max(0, $goal - $current);

        if ($remaining <= 0) {
            return back()->withErrors(['td_amount' => 'You\'ve already reached your Time Deposit goal.']);
        }

        if ($request->amount > $remaining) {
            return back()->withErrors(['td_amount' => 'Amount exceeds the remaining goal balance of ₱' . number_format($remaining, 2)]);
        }

        // ★ CHANGED: No longer sourced from Regular Savings — this is a fresh,
        // independent deposit straight into the Time Deposit, just like a
        // Savings deposit. Regular Savings balance is untouched.
        $newTdBalance = $current + $request->amount;
        $referenceNo = 'TD-DEP-' . strtoupper(bin2hex(random_bytes(3))) . '-' . Carbon::today()->format('Ymd');

        $activeTd->update(['balance' => $newTdBalance]);

        savings_transaction_tbl::create([
            'savings_account_id' => $savingsAccount->id,
            'type' => 'td_lock',
            'amount' => $request->amount,
            'payment_method' => 'Cash', // ★ CHANGED: no longer "Internal Transfer" since no savings funds move
            'balance_after' => $savingsAccount->balance, // ★ CHANGED: savings balance unaffected
            'note' => "Deposited ₱" . number_format($request->amount, 2)
                . " toward Time Deposit goal (₱" . number_format($newTdBalance, 2) . " / ₱" . number_format($goal, 2) . ")",
            'reference_no' => $referenceNo,
            'transaction_date' => Carbon::today(),
            'status' => 'Completed',
        ]);

        AuditLog::log(
            'Member Deposited to Time Deposit',
            "Deposited ₱{$request->amount} toward Time Deposit goal (Ref: {$referenceNo})",
            'savings',
            $savingsAccount->id
        );

        return redirect()->route('TimeDeposit')
            ->with('td_deposit_success', true)
            ->with('td_deposit_amount', $request->amount)
            ->with('td_deposit_reference', $referenceNo)
            ->with('td_new_balance', $newTdBalance);
    }

    /**
     * Open a Time Deposit — locks funds out of Regular Savings into td_balance.
     * Only one active TD per account (matches your current schema).
     */
    public function openTimeDeposit(Request $request)
    {
        $request->validate([
            'term_months' => 'required|integer|in:12',
            'amount' => 'required|numeric|min:1000',
        ]);

        $user = Auth::user();
        $savingsAccount = savings_account_tbl::where('user_id', $user->id)->firstOrFail();

        // ★ CHANGED: only block if there's a genuinely active TD (status + real goal)
        $hasGenuineActiveTd = TimeDeposit::where('savings_account_id', $savingsAccount->id)
            ->where('status', 'active')
            ->exists();

        if ($hasGenuineActiveTd) {
            return back()->withErrors(['amount' => 'You already have an active Time Deposit. Wait for it to mature before opening another.']);
        }

        $setting = \App\Models\Savings_settings_tbl::where('term_months', $request->term_months)
            ->where('savings_type', 'like', 'Time Deposit%')
            ->where('is_active', true)
            ->first();

        if (!$setting) {
            return back()->withErrors(['amount' => 'That Time Deposit term is not currently available.']);
        }

        if ($request->amount < $setting->min_amount) {
            return back()->withErrors(['amount' => 'Minimum goal amount for this term is ₱' . number_format($setting->min_amount, 2)]);
        }

        if ($request->amount <= 0) {
            return back()->withErrors(['amount' => 'Invalid goal amount.']);
        }

        $referenceNo = 'TD-' . strtoupper(bin2hex(random_bytes(3))) . '-' . Carbon::today()->format('Ymd');
        $maturityDate = Carbon::today()->addMonths((int) $request->term_months);

        // ★ No funds move here — this only sets the goal and starts the term.
        // ★ No funds move here — this only sets the goal and starts the term.

        // ★ Keep a permanent history record alongside the live snapshot above
        TimeDeposit::create([
            'savings_account_id' => $savingsAccount->id,
            'goal_amount' => $request->amount,
            'balance' => 0,
            'interest_rate' => $setting->interest_rate,
            'term_months' => (int) $request->term_months,
            'opened_at' => Carbon::today(),
            'maturity_date' => $maturityDate,
            'status' => 'active',
            'reference_no' => $referenceNo,
        ]);

        savings_transaction_tbl::create([
            'savings_account_id' => $savingsAccount->id,
            'type' => 'td_open',
            'amount' => $request->amount,
            'payment_method' => 'Internal Transfer',
            'balance_after' => $savingsAccount->balance, // no funds move on open
            'note' => "Opened Time Deposit goal of ₱" . number_format($request->amount, 2)
                . ", {$request->term_months}-month term, matures " . $maturityDate->format('M d, Y'),
            'reference_no' => $referenceNo,
            'transaction_date' => Carbon::today(),
            'status' => 'completed',   // ← changed from 'active'
        ]);

        AuditLog::log(
            'Member Opened Time Deposit Goal',
            "Set a ₱{$request->amount} Time Deposit goal, {$request->term_months}-month term (Ref: {$referenceNo})",
            'savings',
            $savingsAccount->id
        );

        return redirect()->route('TimeDeposit')
            ->with('td_success', true)
            ->with('td_goal', $request->amount)
            ->with('td_maturity', $maturityDate->format('M d, Y'))
            ->with('td_reference', $referenceNo);
    }

    /**
     * Handle deposit.
     */
    public function deposit(Request $request)
    {

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string|max:255',
            'payment_method' => 'required|string|in:cash,gcash',
            'gcash_proof' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $gcashProofPath = $request->hasFile('gcash_proof')
            ? $request->file('gcash_proof')->store('documents/gcash_proofs', 'public')
            : null;

        $user = Auth::user();
        $savingsAccount = savings_account_tbl::where('user_id', $user->id)->firstOrFail();
        $newBalance = $savingsAccount->balance + $request->amount;
        $referenceNo = $this->generateReferenceNo('deposit');

        $hasShareCapital = \Illuminate\Support\Facades\DB::table('share_capital_account_tbls')
            ->where('user_id', $user->id)
            ->where('status', 'Active')
            ->where('total_shares', '>', 0)
            ->exists();

        if (!$hasShareCapital) {
            return redirect()->route('savings.index')
                ->with('error', 'You must have an active Share Capital account before you can deposit or withdraw savings.');
        }

        $savingsAccount->update(['balance' => $newBalance]);

        savings_transaction_tbl::create([
            'savings_account_id' => $savingsAccount->id,
            'type' => 'deposit',
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'gcash_proof_path' => $gcashProofPath,
            'balance_after' => $newBalance,
            'note' => $request->note,
            'reference_no' => $referenceNo,
            'transaction_date' => Carbon::today(),
            'status' => 'completed',
        ]);

        AuditLog::log(
            'Member Savings Deposit',
            "Deposited ₱{$request->amount} to savings (Ref: {$referenceNo})",
            'savings',
            $savingsAccount->id
        );

        return redirect()->route('savings.index')
            ->with('deposit_success', true)
            ->with('deposit_amount', $request->amount)
            ->with('deposit_reference', $referenceNo)
            ->with('deposit_balance', $newBalance);
    }

    /**
     * Handle withdrawal.
     */
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string|max:255',
            'payment_method' => 'required|string|in:cash,gcash',
            'gcash_proof' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $gcashProofPath = $request->hasFile('gcash_proof')
            ? $request->file('gcash_proof')->store('documents/gcash_proofs', 'public')
            : null;

        $user = Auth::user();
        $savingsAccount = savings_account_tbl::where('user_id', $user->id)->firstOrFail();

        $hasShareCapital = \Illuminate\Support\Facades\DB::table('share_capital_account_tbls')
            ->where('user_id', $user->id)
            ->where('status', 'Active')
            ->where('total_shares', '>', 0)
            ->exists();

        if (!$hasShareCapital) {
            return redirect()->route('savings.index')
                ->with('error', 'You must have an active Share Capital account before you can deposit or withdraw savings.');
        }

        if ($request->amount > $savingsAccount->balance) {
            return back()->withErrors(['amount' => 'Insufficient balance. Available: ₱ ' . number_format($savingsAccount->balance, 2)]);
        }

        $newBalance = $savingsAccount->balance - $request->amount;
        $referenceNo = $this->generateReferenceNo('withdrawal');

        $savingsAccount->update(['balance' => $newBalance]);

        savings_transaction_tbl::create([
            'savings_account_id' => $savingsAccount->id,
            'type' => 'withdrawal',
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'gcash_proof_path' => $gcashProofPath,
            'balance_after' => $newBalance,
            'note' => $request->note,
            'reference_no' => $referenceNo,
            'transaction_date' => Carbon::today(),
            'status' => 'released',
        ]);

        AuditLog::log(
            'Member Savings Withdrawal',
            "Withdrew ₱{$request->amount} from savings (Ref: {$referenceNo})",
            'savings',
            $savingsAccount->id
        );

        return redirect()->route('savings.index')
            ->with('withdraw_success', true)
            ->with('withdraw_amount', $request->amount)
            ->with('withdraw_reference', $referenceNo)
            ->with('withdraw_balance', $newBalance);
    }

    public function payViaGcash(Request $request)
    {
        if (!env('PAYMONGO_SECRET_KEY')) {
            return redirect()->back()->with('error', 'Payment gateway is not configured yet.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'transaction_type' => 'required|in:deposit,withdraw',
            'note' => 'nullable|string|max:255',
        ]);

        $amount = (float) $request->amount;

        session([
            'sav_pending_amount' => $amount,
            'sav_pending_note' => $request->note,
            'sav_pending_type' => $request->transaction_type,
        ]);

        $response = \Illuminate\Support\Facades\Http::withBasicAuth(env('PAYMONGO_SECRET_KEY'), '')
            ->withOptions(['verify' => false])
            ->post('https://api.paymongo.com/v1/sources', [
                'data' => [
                    'attributes' => [
                        'amount' => (int) ($amount * 100),
                        'currency' => 'PHP',
                        'type' => 'gcash',
                        'redirect' => [
                            'success' => route('savings.gcash.success'),
                            'failed' => route('savings.gcash.failed'),
                        ],
                    ],
                ],
            ]);

        $data = $response->json();

        if (isset($data['data']['attributes']['redirect']['checkout_url'])) {
            return redirect($data['data']['attributes']['redirect']['checkout_url']);
        }

        return redirect()->back()->with('error', 'GCash payment failed. Please try again.');
    }

    public function downloadReceipt(string $referenceNo, Request $request)
    {
        $user = Auth::user();

        // Check if this is admin requesting - find transaction by reference_no
        $tx = savings_transaction_tbl::where('reference_no', $referenceNo)->first();

        if (!$tx) {
            // Fall back to member lookup
            $savingsAccount = savings_account_tbl::where('user_id', $user->id)->firstOrFail();
            $tx = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
                ->where('reference_no', $referenceNo)
                ->firstOrFail();
        }

        // Get user for the transaction
        $savingsAccount = savings_account_tbl::find($tx->savings_account_id);
        $transactionUser = $savingsAccount ? Users_tbl::find($savingsAccount->user_id) : null;

        if (!$transactionUser) {
            $transactionUser = $user;
        }

        // Type-specific display config
        $typeConfig = [
            'deposit' => ['label' => 'Deposit', 'title' => 'Deposit Successful!', 'color' => 'green'],
            'withdrawal' => ['label' => 'Withdrawal', 'title' => 'Withdrawal Successful!', 'color' => 'red'],
            'td_lock' => ['label' => 'Time Deposit Lock', 'title' => 'Time Deposit Opened!', 'color' => 'blue'],
            'interest_credit' => ['label' => 'Interest Credit', 'title' => 'Interest Credited!', 'color' => 'blue'],
        ];

        $cfg = $typeConfig[$tx->type] ?? ['label' => ucfirst(str_replace('_', ' ', $tx->type)), 'title' => 'Transaction Complete', 'color' => 'green'];
        $type = $cfg['label'];
        $date = \Carbon\Carbon::parse($tx->transaction_date)->format('F d, Y');
        $time = \Carbon\Carbon::parse($tx->created_at)->format('h:i A');
        $amount = 'PHP ' . number_format($tx->amount, 2);
        $balance = 'PHP ' . number_format($tx->balance_after, 2);
        $note = $tx->note ?? 'N/A';
        $member = $transactionUser->first_name . ' ' . $transactionUser->last_name;
        $isDeposit = $cfg['color'] === 'green';

        // Font paths
        $fontRegular = public_path('Poppins/Poppins-Regular.ttf');
        $fontSemiBold = public_path('Poppins/Poppins-SemiBold.ttf');

        // Canvas size — increased height to fit extra row
        $w = 600;
        $h = 550;
        $img = imagecreatetruecolor($w, $h);

        // Enable antialiasing
        imageantialias($img, true);

        // Colors
        $white = imagecolorallocate($img, 255, 255, 255);
        $green = imagecolorallocate($img, 30, 64, 53);
        $lightGreen = imagecolorallocate($img, 240, 247, 244);
        $red = imagecolorallocate($img, 220, 38, 38);
        $lightRed = imagecolorallocate($img, 254, 242, 242);
        $muted = imagecolorallocate($img, 107, 123, 116);
        $border = imagecolorallocate($img, 226, 232, 229);
        $dark = imagecolorallocate($img, 26, 26, 26);
        $accentClr = $isDeposit ? $green : $red;
        $accentBg = $isDeposit ? $lightGreen : $lightRed;

        $blue = imagecolorallocate($img, 30, 86, 160);
        $lightBlue = imagecolorallocate($img, 235, 244, 255);

        $accentClr = match ($cfg['color']) {
            'green' => $green,
            'red' => $red,
            'blue' => $blue,
            default => $green,
        };
        $accentBg = match ($cfg['color']) {
            'green' => $lightGreen,
            'red' => $lightRed,
            'blue' => $lightBlue,
            default => $lightGreen,
        };

        // Background
        imagefilledrectangle($img, 0, 0, $w, $h, $white);

        // Top accent bar
        imagefilledrectangle($img, 0, 0, $w, 6, $accentClr);

        // Header background strip
        imagefilledrectangle($img, 0, 6, $w, 140, $accentBg);

        // Circle icon background
        imagefilledellipse($img, $w / 2, 80, 80, 80, $accentClr);
        imagefilledellipse($img, $w / 2, 80, 70, 70, $white);

        // Checkmark inside circle
        imageline($img, $w / 2 - 14, 80, $w / 2 - 4, 92, $accentClr);
        imageline($img, $w / 2 - 13, 80, $w / 2 - 3, 92, $accentClr);
        imageline($img, $w / 2 - 4, 92, $w / 2 + 16, 66, $accentClr);
        imageline($img, $w / 2 - 4, 91, $w / 2 + 16, 65, $accentClr);

        // Helper: centered text
        $centerText = function (int $fontSize, string $fontPath, string $text, int $y, $color) use ($img, $w) {
            $bbox = imagettfbbox($fontSize, 0, $fontPath, $text);
            $textWidth = $bbox[2] - $bbox[0];
            $x = ($w - $textWidth) / 2;
            imagettftext($img, $fontSize, 0, (int) $x, $y, $color, $fontPath, $text);
        };

        // Title
        // $title = $isDeposit ? 'Deposit Successful!' : 'Withdrawal Successful!';
        $title = $cfg['title'];
        $centerText(16, $fontSemiBold, $title, 165, $dark);

        // Subtitle
        $sub = 'KMPCATS Cooperative -- Official Receipt';
        $centerText(9, $fontRegular, $sub, 183, $muted);

        // Divider
        imageline($img, 40, 198, $w - 40, 198, $border);

        // Info rows — Time row added after Date
        $rows = [
            ['Reference No.', $referenceNo],
            ['Member', $member],
            ['Date', $date],
            ['Time', $time],       // <-- new row
            ['Type', $type],
            ['Amount', $amount],
            ['Balance After', $balance],
            ['Note', $note],
        ];

        $y = 220;
        foreach ($rows as $row) {
            // Label (left)
            imagettftext($img, 9, 0, 50, $y, $muted, $fontRegular, $row[0]);

            // Value (right-aligned)
            $val = strlen($row[1]) > 38 ? substr($row[1], 0, 38) . '...' : $row[1];
            $bbox = imagettfbbox(9, 0, $fontSemiBold, $val);
            $valW = $bbox[2] - $bbox[0];
            $valX = $w - 50 - $valW;
            $valColor = $row[0] === 'Amount' ? $accentClr : $dark;
            imagettftext($img, 9, 0, (int) $valX, $y, $valColor, $fontSemiBold, $val);

            // Draw line BELOW the text
            imageline($img, 50, $y + 10, $w - 50, $y + 10, $border);

            $y += 30;
        }

        // Bottom note
        $note1 = 'This receipt is system-generated and serves as';
        $note2 = 'official proof of your transaction.';
        $centerText(8, $fontRegular, $note1, $h - 38, $muted);
        $centerText(8, $fontRegular, $note2, $h - 24, $muted);

        // Bottom bar
        imagefilledrectangle($img, 0, $h - 20, $w, $h, $accentClr);
        $foot = 'KMPCATS Cooperative Management System';
        $centerText(8, $fontRegular, $foot, $h - 6, $white);

        // Output as JPG
        $filename = "Receipt-{$referenceNo}.jpg";
        ob_start();
        imagejpeg($img, null, 95);
        $imageData = ob_get_clean();
        imagedestroy($img);

        // Check if request is for inline view (for admin modal display)
        if ($request->query('view') === 'inline') {
            $base64 = 'data:image/jpeg;base64,' . base64_encode($imageData);
            return response()->json(['image' => $base64]);
        }

        return response($imageData, 200, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Handle admin savings transaction (deposit/withdrawal for any member).
     */
    public function adminStoreSavings(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:users_tbls,id',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|string|in:deposit,withdrawal',
            'payment_method' => ['nullable', 'string', Rule::requiredIf($request->type === 'deposit'), 'in:cash'],
            'note' => 'nullable|string|max:255',
        ]);

        // Get member's savings account
        $savingsAccount = savings_account_tbl::where('user_id', $request->member_id)->first();

        // If no savings account exists, create one
        if (!$savingsAccount) {
            $savingsAccount = savings_account_tbl::create([
                'user_id' => $request->member_id,
                'balance' => 0,
                'status' => 'active',
                'opened_at' => Carbon::today(),
            ]);
        }

        $amount = $request->amount;
        $type = $request->type;

        // Handle withdrawal - check balance
        if ($type === 'withdrawal') {
            if ($savingsAccount->balance < $amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance. Available: ₱' . number_format($savingsAccount->balance, 2)
                ], 422);
            }
            $newBalance = $savingsAccount->balance - $amount;
        } else {
            $newBalance = $savingsAccount->balance + $amount;
        }

        // Update balance
        $savingsAccount->update(['balance' => $newBalance]);

        // Generate reference number
        $referenceNo = $this->generateReferenceNo($type);

        // Create transaction record
        $transaction = savings_transaction_tbl::create([
            'savings_account_id' => $savingsAccount->id,
            'type' => $type,
            'amount' => $amount,
            'payment_method' => $request->payment_method,
            'balance_after' => $newBalance,
            'note' => $request->note,
            'reference_no' => $referenceNo,
            'transaction_date' => Carbon::today(),
            'status' => 'Completed', // ★ NEW
        ]);

        $member = Users_tbl::find($request->member_id);
        AuditLog::log(
            'Admin ' . ucfirst($type) . ' Savings',
            ucfirst($type) . " of ₱{$amount} to/from {$member?->first_name} {$member?->last_name} (Ref: {$referenceNo})",
            'savings',
            $savingsAccount->id
        );

        return response()->json([
            'success' => true,
            'message' => ucfirst($type) . ' of ₱' . number_format($amount, 2) . ' successful!',
            'reference_no' => $referenceNo,
            'new_balance' => $newBalance,
        ]);
    }

    /**
     * Member claims a matured Time Deposit — principal + interest goes back to Regular Savings.
     */
    public function claimTimeDeposit(Request $request)
    {
        $user = Auth::user();
        $savingsAccount = savings_account_tbl::where('user_id', $user->id)->firstOrFail();

        $activeTd = TimeDeposit::where('savings_account_id', $savingsAccount->id)
            ->where('status', 'active')
            ->latest('opened_at')
            ->first();

        if (!$activeTd) {
            return back()->withErrors(['td' => "You don't have an active Time Deposit to claim."]);
        }

        if ((float) $activeTd->goal_amount <= 0 && (float) $activeTd->balance <= 0) {
            return back()->withErrors(['td' => 'This Time Deposit has no funds to claim.']);
        }

        $maturityDate = $activeTd->maturity_date ? Carbon::parse($activeTd->maturity_date) : null;

        if (!$maturityDate || $maturityDate->gt(Carbon::today())) {
            return back()->withErrors([
                'td' => 'This Time Deposit is not yet matured. It will be available on '
                    . ($maturityDate ? $maturityDate->format('M d, Y') : 'N/A') . '.'
            ]);
        }

        $principal = (float) $activeTd->balance;
        $rate = (float) $activeTd->interest_rate;
        $termMonths = (int) $activeTd->term_months;

        // Interest for the full locked term, at the rate snapshotted when the TD was opened
        $interest = round($principal * ($rate / 100) * ($termMonths / 12), 2);
        $totalRelease = $principal + $interest;

        $newBalance = $savingsAccount->balance + $totalRelease;
        $referenceNo = 'TD-CLM-' . strtoupper(bin2hex(random_bytes(3))) . '-' . Carbon::today()->format('Ymd');

        // ★ RESTORED: pay out to Regular Savings and fully reset the TD slot
        $savingsAccount->update(['balance' => $newBalance]);

        $activeTd->update([
            'balance' => 0,
            // ★ NEW: preserve what was actually released, so history/UI can
            // still show the real payout amount after balance resets to 0.
            'claimed_amount' => $totalRelease,
            'claimed_principal' => $principal,
            'claimed_interest' => $interest,
            'status' => 'claimed',
            'claim_reference_no' => $referenceNo,
            'claimed_at' => Carbon::now(),
        ]);

        savings_transaction_tbl::create([
            'savings_account_id' => $savingsAccount->id,
            'type' => 'td_release',
            'amount' => $totalRelease,
            'payment_method' => 'Internal Transfer',
            'balance_after' => $newBalance,
            'note' => "Time Deposit matured — principal ₱" . number_format($principal, 2)
                . " + interest ₱" . number_format($interest, 2),
            'reference_no' => $referenceNo,
            'transaction_date' => Carbon::today(),
            'status' => 'completed',
        ]);

        AuditLog::log(
            'Member Claimed Time Deposit',
            "Claimed matured Time Deposit: principal ₱{$principal} + interest ₱{$interest} = ₱{$totalRelease} (Ref: {$referenceNo})",
            'savings',
            $savingsAccount->id
        );

        return redirect()->route('savings.index')
            ->with('td_claim_success', true)
            ->with('td_claim_amount', $totalRelease)
            ->with('td_claim_reference', $referenceNo);
    }

    /**
     * Get member's current savings balance.
     */
    public function getMemberBalance($memberId)
    {
        $savingsAccount = savings_account_tbl::where('user_id', $memberId)->first();
        $balance = $savingsAccount ? $savingsAccount->balance : 0;
        $member = Users_tbl::with('otherinfo')->find($memberId);
        return response()->json([
            'balance' => $balance,
            'contact_no' => $member?->otherinfo?->contact_no,
        ]);
    }

    /**
     * Get member share capital balance for AJAX.
     */
    public function getMemberShareCapitalBalance($memberId)
    {
        $account = share_capital_account_tbl::where('user_id', $memberId)->first();
        $balance = $account ? $account->total_amount : 0;
        return response()->json(['balance' => $balance]);
    }

    /**
     * Convert/transfer a portion of a member's Savings into Share Capital.
     *
     * Atomic: savings balance, share capital balance, and BOTH ledger records
     * change together or not at all. Idempotent via idempotency_key (used to
     * build the shared SCP-CONV- reference on both sides of the ledger).
     */
    public function convertToShareCapital(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:users_tbls,id',
            'amount' => 'required|numeric|min:1',
            'idempotency_key' => 'nullable|string|max:64',
        ]);

        $memberId = $request->member_id;
        $amount = (float) $request->amount;
        $amountPerShare = ShareCapital::PAR_VALUE;
        $conversionType = ShareCapital::CONVERSION_TYPE;

        $savingsAccount = savings_account_tbl::where('user_id', $memberId)->first();

        if (!$savingsAccount) {
            return response()->json([
                'success' => false,
                'message' => 'Member does not have a savings account.',
            ], 422);
        }

        if ($amount > $savingsAccount->balance) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient savings balance. Available: ₱' . number_format($savingsAccount->balance, 2),
            ], 422);
        }

        $shares = (int) ($amount / $amountPerShare);
        $convertedAmount = $shares * $amountPerShare;

        if ($shares < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum conversion amount is ₱' . number_format($amountPerShare, 2) . ' (1 share).',
            ], 422);
        }

        if ($convertedAmount < $amount) {
            $remainder = $amount - $convertedAmount;
        } else {
            $remainder = 0;
        }

        $now = Carbon::now();

        // Shared reference on BOTH ledger records so the two sides trace to one operation.
        // When an idempotency key is supplied, reuse it to build the reference so a retry
        // resolves to the same reference and is detected as already processed.
        $referenceNo = $request->idempotency_key
            ? 'SCP-CONV-' . strtoupper($request->idempotency_key)
            : 'SCP-CONV-' . strtoupper(bin2hex(random_bytes(8)));

        // Idempotency guard: a previous successful run already wrote both ledger rows
        // with this reference. Replay without touching balances again.
        $alreadyProcessed = share_capital_transaction_tbl::where('reference_no', $referenceNo)->exists()
            || savings_transaction_tbl::where('reference_no', $referenceNo)->exists();

        if ($alreadyProcessed) {
            $existingSc = share_capital_transaction_tbl::where('reference_no', $referenceNo)->first();

            return response()->json([
                'success' => true,
                'message' => 'This conversion was already processed (Ref: ' . $referenceNo . '). No changes were made.',
                'converted_amount' => $convertedAmount,
                'shares' => $shares,
                'reference_no' => $referenceNo,
                'remainder' => $remainder,
                'already_processed' => true,
                'share_capital_account_id' => $existingSc ? $existingSc->share_capital_account_id : null,
            ]);
        }

        DB::beginTransaction();

        try {
            $savingsNewBalance = $savingsAccount->balance - $convertedAmount;
            $savingsAccount->update(['balance' => $savingsNewBalance]);

            savings_transaction_tbl::create([
                'savings_account_id' => $savingsAccount->id,
                'type' => $conversionType,
                'amount' => $convertedAmount,
                'payment_method' => 'Internal Transfer',
                'balance_after' => $savingsNewBalance,
                'note' => 'Transferred to Share Capital',
                'reference_no' => $referenceNo,
                'transaction_date' => $now->toDateString(),
                'status' => 'Completed',
            ]);

            $scAccount = share_capital_account_tbl::where('user_id', $memberId)->first();

            if ($scAccount) {
                $scAccount->update([
                    'total_shares' => $scAccount->total_shares + $shares,
                    'total_amount' => $scAccount->total_amount + $convertedAmount,
                    'status' => 'Active',
                ]);
                $scAccountId = $scAccount->id;
            } else {
                $scAccount = share_capital_account_tbl::create([
                    'user_id' => $memberId,
                    'total_shares' => $shares,
                    'total_amount' => $convertedAmount,
                    'status' => 'Active',
                ]);
                $scAccountId = $scAccount->id;
            }

            share_capital_transaction_tbl::create([
                'share_capital_account_id' => $scAccountId,
                'type' => $conversionType,
                'shares' => $shares,
                'amount_per_share' => $amountPerShare,
                'total_amount' => $convertedAmount,
                'payment_method' => 'Internal Transfer',
                'reference_no' => $referenceNo,
                'note' => 'Converted from Savings',
                'status' => 'Completed',
                'transaction_date' => $now->toDateString(),
            ]);

            DB::commit();

            $member = Users_tbl::find($memberId);

            return response()->json([
                'success' => true,
                'message' => 'Successfully converted ₱' . number_format($convertedAmount, 2) . ' (' . $shares . ' share(s)) to Share Capital.',
                'converted_amount' => $convertedAmount,
                'shares' => $shares,
                'reference_no' => $referenceNo,
                'remainder' => $remainder,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Conversion failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}