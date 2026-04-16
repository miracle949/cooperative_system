<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\share_capital_transaction_tbl;
use App\Models\share_capital_account_tbl;
use Carbon\Carbon;

class ShareCapital extends Controller
{
    /**
     * Show the Share Capital form (first-time subscription only).
     */
    public function index()
    {
        $memberId = Auth::id();

        $account = DB::table('share_capital_account_tbls')
            ->where('user_id', $memberId)
            ->first();

        $currentBalance = $account->total_amount ?? 0;
        $currentShares = $account->total_shares ?? 0;

        // Dividend rate
        $dividendRateRecord = null;
        if ($this->tableExists('dividend_rates_tbls')) {
            $dividendRateRecord = DB::table('dividend_rates_tbls')
                ->orderByDesc('effective_year')
                ->orderByDesc('created_at')
                ->first();
        }
        $dividendRate = $dividendRateRecord->rate ?? 8.5;
        $dividendRateYear = $dividendRateRecord->effective_year ?? now()->year;

        // Rate history
        $rateHistory = collect();
        if ($this->tableExists('dividend_rates_tbls')) {
            $rateHistory = DB::table('dividend_rates_tbls')
                ->orderByDesc('effective_year')
                ->limit(5)
                ->get();
        }

        // Dividend history
        $dividendHistory = collect();
        if ($this->tableExists('dividend_histories_tbls') && $account) {
            $dividendHistory = DB::table('dividend_histories_tbls')
                ->where('share_capital_account_id', $account->id)
                ->orderByDesc('year')
                ->orderByDesc('semester')
                ->get();
        }

        // Contributions
        $contributions = DB::table('share_capital_transaction_tbls')
            ->where('share_capital_account_id', $account->id ?? 0)
            ->orderByDesc('transaction_date')
            ->get();

        // Last dividend
        $lastDividend = $dividendHistory->where('status', 'Paid')->sortByDesc(fn($d) => $d->date_paid)->first();
        $lastDividendAmount = $lastDividend->dividend_amount ?? null;
        $lastDividendDate = $lastDividend ? Carbon::parse($lastDividend->date_paid)->format('M d, Y') : null;
        $lastDividendPeriod = $lastDividend->period_label ?? null;

        // Next dividend date
        $today = Carbon::today();
        $jun15ThisYear = Carbon::create($today->year, 6, 15);
        $dec15ThisYear = Carbon::create($today->year, 12, 15);
        $jun15NextYear = Carbon::create($today->year + 1, 6, 15);

        if ($today->lte($jun15ThisYear)) {
            $nextDividendDate = $jun15ThisYear;
            $nextDividendPeriod = '1st Semester ' . $today->year;
            $nextDividendSemester = 1;
        } elseif ($today->lte($dec15ThisYear)) {
            $nextDividendDate = $dec15ThisYear;
            $nextDividendPeriod = '2nd Semester ' . $today->year;
            $nextDividendSemester = 2;
        } else {
            $nextDividendDate = $jun15NextYear;
            $nextDividendPeriod = '1st Semester ' . ($today->year + 1);
            $nextDividendSemester = 1;
        }

        $projectedNextDividend = round($currentBalance * ($dividendRate / 100) / 2, 2);
        $totalDividendsEarned = $dividendHistory->where('status', 'Paid')->sum('dividend_amount');

        $prevDividendDate = $lastDividend
            ? Carbon::parse($lastDividend->date_paid)
            : $nextDividendDate->copy()->subMonths(6);
        $prevDividendPeriod = $lastDividend->period_label
            ?? ($nextDividendSemester === 1
                ? '2nd Semester ' . ($nextDividendDate->year - 1)
                : '1st Semester ' . $nextDividendDate->year);

        $futureDate2 = $nextDividendDate->copy()->addMonths(6);
        $futurePeriod2 = $nextDividendSemester === 1
            ? '2nd Semester ' . $nextDividendDate->year
            : '1st Semester ' . ($nextDividendDate->year + 1);

        return view('ShareCapitalForm.share_capital_form', compact(
            'currentBalance',
            'currentShares',
            'contributions',
            'dividendRate',
            'dividendRateYear',
            'rateHistory',
            'dividendHistory',
            'lastDividend',
            'lastDividendAmount',
            'lastDividendDate',
            'lastDividendPeriod',
            'nextDividendDate',
            'nextDividendPeriod',
            'projectedNextDividend',
            'totalDividendsEarned',
            'prevDividendDate',
            'prevDividendPeriod',
            'futureDate2',
            'futurePeriod2',
        ));
    }

    /**
     * Show the member Share Capital page (Deposit/Withdrawal).
     */
    public function memberIndex()
    {
        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;

        $memberId = Auth::id();

        $account = DB::table('share_capital_account_tbls')
            ->where('user_id', $memberId)
            ->first();

        $currentBalance = $account->total_amount ?? 0;
        $currentShares = $account->total_shares ?? 0;

        $contributions = DB::table('share_capital_transaction_tbls')
            ->where('share_capital_account_id', $account->id ?? 0)
            ->orderByDesc('transaction_date')
            ->get();

        // ─────────────────────────────────────────────────────────────
        // DIVIDEND DATA
        // ─────────────────────────────────────────────────────────────

        $dividendRateRecord = null;
        if ($this->tableExists('dividend_rates_tbls')) {
            $dividendRateRecord = DB::table('dividend_rates_tbls')
                ->orderByDesc('effective_year')
                ->orderByDesc('created_at')
                ->first();
        }
        $dividendRate = $dividendRateRecord->rate ?? 8.5;
        $dividendRateYear = $dividendRateRecord->effective_year ?? now()->year;

        $rateHistory = collect();
        if ($this->tableExists('dividend_rates_tbls')) {
            $rateHistory = DB::table('dividend_rates_tbls')
                ->orderByDesc('effective_year')
                ->limit(5)
                ->get();
        }

        $dividendHistory = collect();
        if ($this->tableExists('dividend_histories_tbls') && $account) {
            $dividendHistory = DB::table('dividend_histories_tbls')
                ->where('share_capital_account_id', $account->id)
                ->orderByDesc('year')
                ->orderByDesc('semester')
                ->get();
        }

        $lastDividend = $dividendHistory
            ->where('status', 'Paid')
            ->sortByDesc(fn($d) => $d->date_paid)
            ->first();

        $lastDividendAmount = $lastDividend->dividend_amount ?? null;
        $lastDividendDate = $lastDividend
            ? Carbon::parse($lastDividend->date_paid)->format('M d, Y')
            : null;
        $lastDividendPeriod = $lastDividend->period_label ?? null;

        $today = Carbon::today();
        $jun15ThisYear = Carbon::create($today->year, 6, 15);
        $dec15ThisYear = Carbon::create($today->year, 12, 15);
        $jun15NextYear = Carbon::create($today->year + 1, 6, 15);

        if ($today->lte($jun15ThisYear)) {
            $nextDividendDate = $jun15ThisYear;
            $nextDividendPeriod = '1st Semester ' . $today->year;
            $nextDividendSemester = 1;
        } elseif ($today->lte($dec15ThisYear)) {
            $nextDividendDate = $dec15ThisYear;
            $nextDividendPeriod = '2nd Semester ' . $today->year;
            $nextDividendSemester = 2;
        } else {
            $nextDividendDate = $jun15NextYear;
            $nextDividendPeriod = '1st Semester ' . ($today->year + 1);
            $nextDividendSemester = 1;
        }

        $projectedNextDividend = round($currentBalance * ($dividendRate / 100) / 2, 2);
        $totalDividendsEarned = $dividendHistory->where('status', 'Paid')->sum('dividend_amount');

        $prevDividendDate = $lastDividend
            ? Carbon::parse($lastDividend->date_paid)
            : ($nextDividendDate->copy()->subMonths(6));
        $prevDividendPeriod = $lastDividend->period_label
            ?? ($nextDividendSemester === 1
                ? '2nd Semester ' . ($nextDividendDate->year - 1)
                : '1st Semester ' . $nextDividendDate->year);

        $futureDate2 = $nextDividendDate->copy()->addMonths(6);
        $futurePeriod2 = $nextDividendSemester === 1
            ? '2nd Semester ' . $nextDividendDate->year
            : '1st Semester ' . ($nextDividendDate->year + 1);

        // ─────────────────────────────────────────────────────────────

        return view(
            'members_components.share_capital',
            [
                "username" => $username,
                "email" => $email
            ],
            compact(
                'currentBalance',
                'currentShares',
                'contributions',
                'dividendRate',
                'dividendRateYear',
                'rateHistory',
                'dividendHistory',
                'lastDividend',
                'lastDividendAmount',
                'lastDividendDate',
                'lastDividendPeriod',
                'nextDividendDate',
                'nextDividendPeriod',
                'projectedNextDividend',
                'totalDividendsEarned',
                'prevDividendDate',
                'prevDividendPeriod',
                'futureDate2',
                'futurePeriod2',
            )
        );
    }

    /**
     * Handle Cash form submission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shares' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'in:Subscription,Deposit,Withdrawal'],
            'payment_method' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        if (strtolower($validated['payment_method']) === 'gcash') {
            return $this->payViaGcash($request);
        }

        $memberId = Auth::id();
        $amountPerShare = 1000;
        $shares = (int) $validated['shares'];
        $totalAmount = $shares * $amountPerShare;
        $now = Carbon::now();
        $referenceNo = $this->generateReferenceNo();
        $type = $validated['type'];

        $account = DB::table('share_capital_account_tbls')
            ->where('user_id', $memberId)
            ->first();

        $currentBalance = $account->total_amount ?? 0;

        // ── FIX 1: Block withdrawal when balance is 0 ──────────────────
        if ($type === 'Withdrawal' && $currentBalance <= 0) {
            return redirect()->back()
                ->with('error', 'You cannot withdraw because your current balance is ₱0.')
                ->withInput();
        }

        // ── FIX 2: Block withdrawal exceeding current balance ──────────
        if ($type === 'Withdrawal' && $totalAmount > $currentBalance) {
            return redirect()->back()
                ->with('error', 'Withdrawal amount (₱' . number_format($totalAmount, 0) . ') exceeds your current balance (₱' . number_format($currentBalance, 0) . ').')
                ->withInput();
        }

        DB::beginTransaction();

        try {
            if ($account) {
                // ── FIX 3: Only set Active & update balance on Deposit/Subscription ──
                if ($type !== 'Withdrawal') {
                    DB::table('share_capital_account_tbls')
                        ->where('user_id', $memberId)
                        ->update([
                            'total_shares' => $account->total_shares + $shares,
                            'total_amount' => $account->total_amount + $totalAmount,
                            'status' => 'Active',
                            'updated_at' => $now,
                        ]);

                    // Also set membership_status to Active in users table
                    if ($type === 'Subscription') {
                        DB::table('otherinfo_tbls')
                            ->where('user_id', $memberId)
                            ->update(['membership_status' => 'Active']);
                    }
                }
                $accountId = $account->id;
            } else {
                $accountId = DB::table('share_capital_account_tbls')->insertGetId([
                    'user_id' => $memberId,
                    'total_shares' => $shares,
                    'total_amount' => $totalAmount,
                    'status' => 'Active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // Set membership_status to Active on first deposit/subscription
                // if ($type === 'Subscription') {
                //     DB::table('otherinfo_tbls')
                //         ->where('user_id', $memberId)
                //         ->update(['membership_status' => 'Active']);
                // }
            }

            // ── FIX 4: Deposit/Subscription → Completed; Withdrawal → Pending ──
            $transactionStatus = ($type === 'Withdrawal') ? 'Pending' : 'Completed';

            DB::table('share_capital_transaction_tbls')->insert([
                'share_capital_account_id' => $accountId,
                'type' => $type,
                'shares' => $shares,
                'amount_per_share' => $amountPerShare,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'reference_no' => $referenceNo,
                'note' => $validated['note'] ?? null,
                'status' => $transactionStatus,
                'transaction_date' => $now->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::commit();

            $memberName = $this->resolveMemberName();
            // $redirectRoute = ($type === 'Subscription') ? 'share_capital.index' : 'ShareCapitalMember';
            $redirectRoute = 'ShareCapitalMember';

            return redirect()->route($redirectRoute)
                ->with('success', 'Share capital request submitted successfully!')
                ->with('sc_receipt_shares', $shares)
                ->with('sc_receipt_amount', $totalAmount)
                ->with('sc_receipt_method', ucfirst($validated['payment_method']))
                ->with('sc_receipt_ref', $referenceNo)
                ->with('sc_receipt_member', $memberName)
                ->with('sc_receipt_type', $type)
                ->with('sc_receipt_status', $transactionStatus); // pass status to blade

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Something went wrong: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Redirect user to GCash checkout via PayMongo.
     */
    public function payViaGcash(Request $request)
    {
        if (!env('PAYMONGO_SECRET_KEY')) {
            return redirect()->back()->with('error', 'Payment gateway is not configured yet.');
        }

        $shares = (int) $request->input('shares', 1);
        $totalAmount = $shares * 1000;
        $type = $request->input('type', 'Subscription');

        // ── FIX 5: Block GCash withdrawal when balance is 0 or insufficient ──
        if ($type === 'Withdrawal') {
            $memberId = Auth::id();
            $account = DB::table('share_capital_account_tbls')
                ->where('user_id', $memberId)
                ->first();

            $currentBalance = $account->total_amount ?? 0;

            if ($currentBalance <= 0) {
                return redirect()->back()
                    ->with('error', 'You cannot withdraw because your current balance is ₱0.')
                    ->withInput();
            }

            if ($totalAmount > $currentBalance) {
                return redirect()->back()
                    ->with('error', 'Withdrawal amount (₱' . number_format($totalAmount, 0) . ') exceeds your current balance (₱' . number_format($currentBalance, 0) . ').')
                    ->withInput();
            }
        }

        session([
            'sc_pending_shares' => $shares,
            'sc_pending_note' => $request->input('note'),
            'sc_pending_type' => $type,
        ]);

        $response = Http::withBasicAuth(env('PAYMONGO_SECRET_KEY'), '')
            ->post('https://api.paymongo.com/v1/sources', [
                'data' => [
                    'attributes' => [
                        'amount' => $totalAmount * 100,
                        'currency' => 'PHP',
                        'type' => 'gcash',
                        'redirect' => [
                            'success' => route('share_capital.gcash.success'),
                            'failed' => route('share_capital.gcash.failed'),
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

    /**
     * Handle successful GCash payment callback.
     */
    public function gcashSuccess(Request $request)
    {
        $memberId = Auth::id();
        $amountPerShare = 1000;
        $shares = (int) session('sc_pending_shares', 1);
        $note = session('sc_pending_note');
        $type = session('sc_pending_type', 'Subscription');
        $totalAmount = $shares * $amountPerShare;
        $now = Carbon::now();
        $referenceNo = 'GCASH-' . now()->format('YmdHis');

        session()->forget(['sc_pending_shares', 'sc_pending_note', 'sc_pending_type']);

        $account = DB::table('share_capital_account_tbls')
            ->where('user_id', $memberId)
            ->first();

        DB::beginTransaction();

        try {
            if ($account) {
                if ($type !== 'Withdrawal') {
                    DB::table('share_capital_account_tbls')
                        ->where('user_id', $memberId)
                        ->update([
                            'total_shares' => $account->total_shares + $shares,
                            'total_amount' => $account->total_amount + $totalAmount,
                            'status' => 'Active',
                            'updated_at' => $now,
                        ]);

                    // Set membership_status to Active on deposit
                    if ($type === 'Subscription') {
                        DB::table('otherinfo_tbls')
                            ->where('user_id', $memberId)
                            ->update(['membership_status' => 'Active']);
                    }
                }
                $accountId = $account->id;
            } else {
                $accountId = DB::table('share_capital_account_tbls')->insertGetId([
                    'user_id' => $memberId,
                    'total_shares' => $shares,
                    'total_amount' => $totalAmount,
                    'status' => 'Active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // Set membership_status to Active on first deposit
                // if ($type === 'Subscription') {
                //     DB::table('otherinfo_tbls')
                //         ->where('user_id', $memberId)
                //         ->update(['membership_status' => 'Active']);
                // }
            }

            // Deposit/Subscription → Completed; Withdrawal → Pending
            $transactionStatus = ($type === 'Withdrawal') ? 'Pending' : 'Completed';

            DB::table('share_capital_transaction_tbls')->insert([
                'share_capital_account_id' => $accountId,
                'type' => $type,
                'shares' => $shares,
                'amount_per_share' => $amountPerShare,
                'total_amount' => $totalAmount,
                'payment_method' => 'GCash',
                'reference_no' => $referenceNo,
                'note' => $note,
                'status' => $transactionStatus,
                'transaction_date' => $now->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::commit();

            $memberName = $this->resolveMemberName();
            // $redirectRoute = ($type === 'Subscription') ? 'share_capital.index' : 'ShareCapitalMember';
            $redirectRoute = 'ShareCapitalMember';

            return redirect()->route($redirectRoute)
                ->with('success', 'GCash payment successful!')
                ->with('sc_receipt_shares', $shares)
                ->with('sc_receipt_amount', $totalAmount)
                ->with('sc_receipt_method', 'GCash')
                ->with('sc_receipt_ref', $referenceNo)
                ->with('sc_receipt_member', $memberName)
                ->with('sc_receipt_type', $type)
                ->with('sc_receipt_status', $transactionStatus);

        } catch (\Throwable $e) {
            DB::rollBack();
            // $redirectRoute = ($type === 'Subscription') ? 'share_capital.index' : 'ShareCapitalMember';
            $redirectRoute = 'ShareCapitalMember';
            return redirect()->route($redirectRoute)
                ->with('error', 'GCash payment was received but failed to save. Please contact support.');
        }
    }

    /**
     * Handle failed GCash payment callback.
     */
    public function gcashFailed(Request $request)
    {
        $type = session('sc_pending_type', 'Subscription');
        session()->forget(['sc_pending_shares', 'sc_pending_note', 'sc_pending_type']);

        $redirectRoute = ($type === 'Subscription') ? 'share_capital.index' : 'ShareCapitalMember';
        return redirect()->route($redirectRoute)
            ->with('error', 'GCash payment failed. Please try again.');
    }

    /**
     * Show the Share Capital form for a specific member via email link.
     */
    public function showForMember($id)
    {
        $user = \App\Models\Users_tbl::findOrFail($id);

        $account = DB::table('share_capital_account_tbls')
            ->where('user_id', $id)
            ->first();

        $currentBalance = $account->total_amount ?? 0;
        $currentShares = $account->total_shares ?? 0;

        $dividendRateRecord = null;
        if ($this->tableExists('dividend_rates_tbls')) {
            $dividendRateRecord = DB::table('dividend_rates_tbls')
                ->orderByDesc('effective_year')
                ->orderByDesc('created_at')
                ->first();
        }
        $dividendRate = $dividendRateRecord->rate ?? 8.5;

        if (!Auth::check()) {
            Auth::loginUsingId($id);
        }

        return view('ShareCapitalForm.share_capital_form', compact(
            'currentBalance',
            'currentShares',
            'dividendRate',
            'user'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    private function generateReferenceNo(): string
    {
        return 'SC-' . strtoupper(uniqid()) . '-' . now()->format('Ymd');
    }

    private function resolveMemberName(): string
    {
        $name = trim((Auth::user()->first_name ?? '') . ' ' . (Auth::user()->last_name ?? ''));
        return $name ?: (Auth::user()->name ?? Auth::user()->username ?? 'Member');
    }

    private function tableExists(string $table): bool
    {
        try {
            return DB::getSchemaBuilder()->hasTable($table);
        } catch (\Throwable) {
            return false;
        }
    }
}