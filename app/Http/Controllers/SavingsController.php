<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\savings_account_tbl;
use App\Models\savings_transaction_tbl;
use Carbon\Carbon;

class SavingsController extends Controller
{
    /**
     * Show the savings page.
     */
    public function index()
    {
        $user = Auth::user();

        $first_name = Auth::check() ? Auth::user()->first_name : null;

        // Get the savings account of the logged-in user
        $savingsAccount = savings_account_tbl::where('member_id', $user->id)->first();

        // If the user has no savings account yet, create one automatically
        if (!$savingsAccount) {
            $savingsAccount = savings_account_tbl::create([
                'member_id' => $user->id,
                'balance' => 0.00,
                'status' => 'active',
                'opened_at' => Carbon::today(),
            ]);
        }

        // Get all transactions grouped by Month Year (e.g. "November 2024")
        $groupedTransactions = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
            ->orderBy('transaction_date', 'desc')
            ->get()
            ->groupBy(function ($tx) {
                return Carbon::parse($tx->transaction_date)->format('F Y');
            });

        // ✅ CORRECT — use the raw expression directly in groupByRaw
        $totalMonths = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
            ->groupByRaw("DATE_FORMAT(transaction_date, '%Y-%m')")
            ->count();

        // Monthly average (avoid division by zero)
        $monthlyAverage = $totalMonths > 0
            ? $savingsAccount->balance / $totalMonths
            : 0;

        // ✅ Updated to match your actual blade file location:
        // resources/views/members_components/savings.blade.php

        // Last transaction date
        $lastUpdated = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
            ->orderBy('transaction_date', 'desc')
            ->value('transaction_date');

        $lastUpdated = $lastUpdated
            ? Carbon::parse($lastUpdated)->diffForHumans()
            : 'No transactions yet';

        // $monthsActive = Carbon::parse($savingsAccount->opened_at)->diffInMonths(Carbon::today());
        // ✅ FIX - rounds up to nearest whole month (so 0.19 = 1)
        $monthsActive = (int) ceil(Carbon::parse($savingsAccount->opened_at)->floatDiffInMonths(Carbon::today()));


        return view(
            'members_components.savings',
            ["first_name" => $first_name],

            compact(
                'savingsAccount',
                'groupedTransactions',
                'totalMonths',
                'monthlyAverage',
                'lastUpdated',
                'monthsActive'
            )
        );
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $savingsAccount = savings_account_tbl::where('member_id', $user->id)->firstOrFail();

        // Calculate new balance
        $newBalance = $savingsAccount->balance + $request->amount;

        // Update the savings account balance
        $savingsAccount->update([
            'balance' => $newBalance,
        ]);

        // Record transaction
        savings_transaction_tbl::create([
            'savings_account_id' => $savingsAccount->id,
            'type' => 'deposit',
            'amount' => $request->amount,
            'balance_after' => $newBalance,
            'note' => $request->note,
            'transaction_date' => Carbon::today(),
        ]);

        return redirect()->route('savings.index')
            ->with('deposit_success', true)
            ->with('deposit_amount', $request->amount);
    }

    /**
     * Handle withdrawal.
     */
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $savingsAccount = savings_account_tbl::where('member_id', $user->id)->firstOrFail();

        // Check sufficient balance
        if ($request->amount > $savingsAccount->balance) {
            return back()
                ->withErrors(['amount' => 'Insufficient balance. Available: ₱ ' . number_format($savingsAccount->balance, 2)])
                ->withInput();
        }

        // Calculate new balance
        $newBalance = $savingsAccount->balance - $request->amount;

        // Update the savings account balance
        $savingsAccount->update([
            'balance' => $newBalance,
        ]);

        // Record transaction
        savings_transaction_tbl::create([
            'savings_account_id' => $savingsAccount->id,
            'type' => 'withdrawal',
            'amount' => $request->amount,
            'balance_after' => $newBalance,
            'note' => $request->note,
            'transaction_date' => Carbon::today(),
        ]);

        return redirect()->route('savings.index')
            ->with('withdraw_success', true)
            ->with('withdraw_amount', $request->amount);
    }
}