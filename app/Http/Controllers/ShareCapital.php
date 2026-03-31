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

        return view('ShareCapitalForm.share_capital_form', compact('currentBalance', 'currentShares'));
    }

    /**
     * Show the member Share Capital page (Deposit/Withdrawal).
     */
    public function memberIndex()
    {
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

        return view('ShareCapital.share_capital', compact('currentBalance', 'currentShares', 'contributions'));
    }

    /**
     * Handle Cash form submission.
     * Used by BOTH share_capital_form (Subscription) and share_capital modal (Deposit/Withdrawal).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shares' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'in:Subscription,Deposit,Withdrawal'],
            'payment_method' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        // If GCash selected, redirect to GCash handler
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

        DB::beginTransaction();

        try {
            if ($account) {
                // ─── Only modify balance/shares for Subscription & Deposit ───
                // Withdrawal is pending approval — shares/balance unchanged until admin approves
                if ($type !== 'Withdrawal') {
                    DB::table('share_capital_account_tbls')
                        ->where('user_id', $memberId)
                        ->update([
                            'total_shares' => $account->total_shares + $shares,
                            'total_amount' => $account->total_amount + $totalAmount,
                            'status' => 'Active',
                            'updated_at' => $now,
                        ]);
                }

                $accountId = $account->id;
            } else {
                // No existing account — only Subscription can create one
                $accountId = DB::table('share_capital_account_tbls')->insertGetId([
                    'user_id' => $memberId,
                    'total_shares' => $shares,
                    'total_amount' => $totalAmount,
                    'status' => 'Active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            DB::table('share_capital_transaction_tbls')->insert([
                'share_capital_account_id' => $accountId,
                'type' => $type,
                'shares' => $shares,
                'amount_per_share' => $amountPerShare,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'reference_no' => $referenceNo,
                'note' => $validated['note'] ?? null,
                'status' => 'Pending',
                'transaction_date' => $now->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::commit();

            $memberName = trim((Auth::user()->first_name ?? '') . ' ' . (Auth::user()->last_name ?? ''));
            if (!$memberName) {
                $memberName = Auth::user()->name ?? Auth::user()->username ?? 'Member';
            }

            if ($type === 'Subscription') {
                return redirect()->route('share_capital.index')
                    ->with('success', 'Share capital request submitted successfully! It is now pending for approval.')
                    ->with('sc_receipt_shares', $shares)
                    ->with('sc_receipt_amount', $totalAmount)
                    ->with('sc_receipt_method', ucfirst($validated['payment_method']))
                    ->with('sc_receipt_ref', $referenceNo)
                    ->with('sc_receipt_member', $memberName)
                    ->with('sc_receipt_type', $type);
            } else {
                return redirect()->route('ShareCapitalMember')
                    ->with('success', 'Share capital request submitted successfully! It is now pending for approval.')
                    ->with('sc_receipt_shares', $shares)
                    ->with('sc_receipt_amount', $totalAmount)
                    ->with('sc_receipt_method', ucfirst($validated['payment_method']))
                    ->with('sc_receipt_ref', $referenceNo)
                    ->with('sc_receipt_member', $memberName)
                    ->with('sc_receipt_type', $type);
            }

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
        $amountPerShare = 1000;
        $totalAmount = $shares * $amountPerShare;

        // ✅ Persist type in session so gcashSuccess() uses it correctly
        session([
            'sc_pending_shares' => $shares,
            'sc_pending_note' => $request->input('note'),
            'sc_pending_type' => $request->input('type', 'Subscription'),
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

        // Clear pending session
        session()->forget(['sc_pending_shares', 'sc_pending_note', 'sc_pending_type']);

        $account = DB::table('share_capital_account_tbls')
            ->where('user_id', $memberId)
            ->first();

        DB::beginTransaction();

        try {
            if ($account) {
                // ─── Only modify balance/shares for Subscription & Deposit ───
                if ($type !== 'Withdrawal') {
                    DB::table('share_capital_account_tbls')
                        ->where('user_id', $memberId)
                        ->update([
                            'total_shares' => $account->total_shares + $shares,
                            'total_amount' => $account->total_amount + $totalAmount,
                            'status' => 'Active',
                            'updated_at' => $now,
                        ]);
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
            }

            DB::table('share_capital_transaction_tbls')->insert([
                'share_capital_account_id' => $accountId,
                'type' => $type,
                'shares' => $shares,
                'amount_per_share' => $amountPerShare,
                'total_amount' => $totalAmount,
                'payment_method' => 'GCash',
                'reference_no' => $referenceNo,
                'note' => $note,
                'status' => 'Pending',
                'transaction_date' => $now->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::commit();

            $memberName = trim((Auth::user()->first_name ?? '') . ' ' . (Auth::user()->last_name ?? ''));
            if (!$memberName) {
                $memberName = Auth::user()->name ?? Auth::user()->username ?? 'Member';
            }

            // Correct redirect: Subscription → form page, Deposit/Withdrawal → member page
            $redirectRoute = ($type === 'Subscription') ? 'share_capital.index' : 'ShareCapitalMember';

            return redirect()->route($redirectRoute)
                ->with('success', 'GCash payment successful! Your share capital request is now pending for approval.')
                ->with('sc_receipt_shares', $shares)
                ->with('sc_receipt_amount', $totalAmount)
                ->with('sc_receipt_method', 'GCash')
                ->with('sc_receipt_ref', $referenceNo)
                ->with('sc_receipt_member', $memberName)
                ->with('sc_receipt_type', $type);

        } catch (\Throwable $e) {
            DB::rollBack();

            $redirectRoute = ($type === 'Subscription') ? 'share_capital.index' : 'ShareCapitalMember';

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
     * URL: /share-capital/{id}
     */
    public function showForMember($id)
    {
        $user = \App\Models\Users_tbl::findOrFail($id);

        $account = DB::table('share_capital_account_tbls')
            ->where('user_id', $id)
            ->first();

        $currentBalance = $account->total_amount ?? 0;
        $currentShares = $account->total_shares ?? 0;

        if (!Auth::check()) {
            Auth::loginUsingId($id);
        }

        return view('ShareCapitalForm.share_capital_form', compact(
            'currentBalance',
            'currentShares',
            'user'
        ));
    }

    /**
     * Generate a unique reference number.
     */
    private function generateReferenceNo(): string
    {
        return 'SC-' . strtoupper(uniqid()) . '-' . now()->format('Ymd');
    }
}