<?php

namespace App\Http\Controllers;

use App\Models\lending_program_tbl;
use App\Models\lending_status_tbl;
use App\Models\lending_repayments_tbl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class lendingController extends Controller
{
    // ─── Shared helper ────────────────────────────────────────────────────────────
    private function getLoanPageData(): array
    {
        $memberId = auth()->id();

        $account = DB::table('share_capital_account_tbls')
            ->where('user_id', $memberId)->first();

        $deposits = DB::table('share_capital_transaction_tbls')
            ->where('share_capital_account_id', $account->id ?? 0)
            ->where('type', 'Deposit')
            ->whereIn('status', ['Completed', 'completed'])
            ->sum('shares') ?? 0;

        $withdrawals = DB::table('share_capital_transaction_tbls')
            ->where('share_capital_account_id', $account->id ?? 0)
            ->where('type', 'Withdrawal')
            ->whereIn('status', ['Approved', 'approved'])
            ->sum('shares') ?? 0;

        $currentShares = $deposits - $withdrawals;
        $canApplyLoan = $currentShares >= 10;

        $maxLoan = 25000;

        // Only Pending and Approved count toward the limit.
        // Declined and Completed do NOT reduce the loanable amount.
        $totalActiveLoan = DB::table('lending_program_tbls')
            ->where('user_id', $memberId)
            ->whereIn('status', ['Pending', 'Approved'])  // ← Completed loans are excluded
            ->sum('lending_amount');

        $remainingLoanable = max(0, $maxLoan - $totalActiveLoan);
        $hasFullyLoaned = $totalActiveLoan >= $maxLoan;

        return compact(
            'currentShares',
            'canApplyLoan',
            'totalActiveLoan',
            'remainingLoanable',
            'hasFullyLoaned'
        );
    }

    // ─── GET: Loan Application page ───────────────────────────────────────────────
    public function index()
    {
        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;

        $dbSettings = DB::table('loan_settings_tbls')->pluck('interest_rate', 'loan_type')->toArray();
        
        $loanSettings = [];
        $typeMap = [
            'Personal Lending' => 'Personal Loan',
            'Emergency Lending' => 'Emergency Loan',
            'Business Lending' => 'Business Loan',
            'Education Lending' => 'Education Loan',
        ];
        
        foreach ($typeMap as $formType => $dbType) {
            $rate = $dbSettings[$dbType] ?? 2;
            $loanSettings[$formType] = $rate / 100;
        }

        return view(
            'members_components.loan_application',
            array_merge(
                ['username' => $username, 'email' => $email, 'loanSettings' => $loanSettings],
                $this->getLoanPageData()
            )
        );
    }

    // ─── POST: Submit loan application ────────────────────────────────────────────
    public function lendingProgram(Request $request)
    {
        $memberId = auth()->id();
        $maxLoan = 25000;

        // Share capital check
        $account = DB::table('share_capital_account_tbls')
            ->where('user_id', $memberId)->first();

        $deposits = DB::table('share_capital_transaction_tbls')
            ->where('share_capital_account_id', $account->id ?? 0)
            ->where('type', 'Deposit')
            ->whereIn('status', ['Completed', 'completed'])
            ->sum('shares') ?? 0;

        $withdrawals = DB::table('share_capital_transaction_tbls')
            ->where('share_capital_account_id', $account->id ?? 0)
            ->where('type', 'Withdrawal')
            ->whereIn('status', ['Approved', 'approved'])
            ->sum('shares') ?? 0;

        $currentShares = $deposits - $withdrawals;

        if ($currentShares < 10) {
            return redirect()->back()
                ->with(
                    'loan_blocked',
                    'You must have at least 10 shares of Share Capital before applying for a loan. ' .
                    'You currently have ' . $currentShares . ' share(s).'
                );
        }

        // Only Pending and Approved count — Declined and Completed are free
        $totalActiveLoan = DB::table('lending_program_tbls')
            ->where('user_id', $memberId)
            ->whereIn('status', ['Pending', 'Approved'])
            ->sum('lending_amount');

        $remainingLoanable = max(0, $maxLoan - $totalActiveLoan);

        if ($totalActiveLoan >= $maxLoan) {
            return redirect()->back()
                ->with(
                    'loan_blocked',
                    'You have reached the maximum loan limit of ₱25,000. ' .
                    'Please repay your existing loan before applying again.'
                );
        }

        if ($request->lending_amount > $remainingLoanable) {
            return redirect()->back()
                ->with(
                    'loan_blocked',
                    'You can only borrow up to ₱' . number_format($remainingLoanable, 2) .
                    ' more based on your current active loans.'
                )
                ->withInput();
        }

        if ($request->lending_amount > $maxLoan) {
            return redirect()->back()
                ->with('loan_blocked', 'The maximum loan amount allowed is ₱25,000.')
                ->withInput();
        }

        // ── Loan term validation per lending type ────────────────────────────────
        // Personal, Emergency, Education → 6 months only
        // Business → 6 months or 12 months
        $lendingType = $request->lending_type;
        $allowedTerms = match ($lendingType) {
            'Business Lending' => ['6 months', '12 months'],
            default => ['6 months'],   // Personal, Emergency, Education
        };

        if (!in_array($request->lending_type_term, $allowedTerms)) {
            return redirect()->back()
                ->with(
                    'loan_blocked',
                    'Invalid loan term selected for ' . $lendingType . '. ' .
                    'Allowed: ' . implode(', ', $allowedTerms) . '.'
                )
                ->withInput();
        }

        try {
            $request->validate([
                'lending_type' => 'nullable|string',
                'lending_amount' => 'nullable|numeric',
                'lending_type_term' => 'nullable|string',
                'monthly_income' => 'nullable|numeric',
                'monthly_payment' => 'nullable|numeric',
                'total_payment' => 'nullable|numeric',
                'total_interest' => 'nullable|numeric',
                'purpose_loan' => 'nullable|string',
                'purpose_loan_others' => 'nullable|string|required_if:purpose_loan,Others',
                'personal_valid_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'personal_proof_of_income' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'emergency_valid_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'emergency_proof_of_income' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'proof_of_emergency' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'business_valid_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'business_proof_of_income' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'business_permit' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'financial_statement' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'education_valid_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'school_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'cor' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ]);

            $storeFile = function ($field, $folder) use ($request) {
                if ($request->hasFile($field)) {
                    return $request->file($field)->store("documents/{$folder}", 'public');
                }
                return null;
            };

            // Map valid_id and proof_of_income fields per lending type
            $validIdField = match ($lendingType) {
                'Personal Lending' => 'personal_valid_id',
                'Emergency Lending' => 'emergency_valid_id',
                'Business Lending' => 'business_valid_id',
                'Education Lending' => 'education_valid_id',
                default => null,
            };

            $proofOfIncomeField = match ($lendingType) {
                'Personal Lending' => 'personal_proof_of_income',
                'Emergency Lending' => 'emergency_proof_of_income',
                'Business Lending' => 'business_proof_of_income',
                default => null,
            };

            $referenceNo = 'LN-' . date('YmdHis') . rand(10, 99);

            lending_program_tbl::create([
                'user_id' => $memberId,
                'reference_no' => $referenceNo,
                'lending_type' => $lendingType,
                'lending_amount' => $request->lending_amount,
                'lending_type_term' => $request->lending_type_term,
                'monthly_income' => $request->monthly_income,
                'monthly_payment' => $request->monthly_payment,
                'total_payment' => $request->total_payment,
                'total_interest' => $request->total_interest,
                'purpose_loan' => $request->purpose_loan === 'Others'
                    ? $request->purpose_loan_others
                    : $request->purpose_loan,
                'status' => 'Pending',
                'valid_id' => $validIdField ? $storeFile($validIdField, 'valid_id') : null,
                'proof_of_income' => $proofOfIncomeField ? $storeFile($proofOfIncomeField, 'proof_of_income') : null,
                'proof_of_emergency' => $storeFile('proof_of_emergency', 'proof_of_emergency'),
                'business_permit' => $storeFile('business_permit', 'business_permit'),
                'financial_statement' => $storeFile('financial_statement', 'financial_statement'),
                'school_id' => $storeFile('school_id', 'school_id'),
                'cor' => $storeFile('cor', 'cor'),
            ]);

            return redirect()->route('LoanApplication')
                ->with('ApplySuccess', true)
                ->with('ReferenceNo', $referenceNo)
                ->with('DateFiled', now()->timezone('Asia/Manila')->format('M d, Y · h:i A'))
                ->with('MemberName', trim(Auth::user()->first_name . ' ' . Auth::user()->last_name) ?: Auth::user()->username);

        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getLine(), $e->getFile());
        }
    }

    // ─── Repayment ────────────────────────────────────────────────────────────────
    public function storeRepayment(Request $request)
    {
        $request->validate([
            'lending_id' => 'required|exists:lending_program_tbls,id',
            'amount_paid' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
        ]);

        lending_repayments_tbl::create([
            'lending_id' => $request->lending_id,
            'user_id' => auth()->id(),
            'payment_number' => $request->payment_number,
            'amount_paid' => $request->amount_paid,
            'payment_date' => now()->format('Y-m-d'),
            'payment_method' => $request->payment_method,
            'reference_no' => $request->reference_no ?: 'RCP-' . now()->format('YmdHis'),
            'notes' => $request->notes,
            'recorded_by' => null,
        ]);

        $status = lending_status_tbl::where('lending_id', $request->lending_id)->first();

        if ($status) {
            $status->total_paid += $request->amount_paid;
            $status->remaining_balance = max(0, $status->remaining_balance - $request->amount_paid);
            $status->payments_made += 1;

            if ($status->remaining_balance <= 0 || $status->payments_made >= $status->total_payments) {
                $status->status = 'Completed';
                $status->payments_made = $status->total_payments; // ← ADD THIS LINE

                lending_program_tbl::where('id', $request->lending_id)
                    ->update(['status' => 'Completed']);
            }

            $status->save();
        }

        return redirect()->route('LoanStatus', ['loan_id' => $request->lending_id])
            ->with('success', 'Payment recorded successfully!');
    }

    // ─── Loan Status page ─────────────────────────────────────────────────────────
    public function loanStatus(Request $request)
    {
        $memberId = auth()->id();
        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;

        // Show Approved AND Completed loans in the sidebar so members
        // can still view their repayment history after fully paying.
        $loans = lending_program_tbl::where('user_id', $memberId)
            ->whereIn('status', ['Approved', 'Completed'])
            ->orderBy('created_at', 'desc')
            ->get();

        $selectedId = $request->get('loan_id');
        $selectedLoan = $selectedId
            ? lending_program_tbl::where('id', $selectedId)->where('user_id', $memberId)->first()
            : $loans->first();

        $lendingStatus = $selectedLoan
            ? lending_status_tbl::where('lending_id', $selectedLoan->id)->first()
            : null;

        if ($selectedLoan && !$lendingStatus && $selectedLoan->status === 'Approved') {
            $termMonths = (int) filter_var($selectedLoan->lending_type_term, FILTER_SANITIZE_NUMBER_INT);
            $interestRate = ($selectedLoan->lending_amount > 0 && $selectedLoan->total_interest > 0)
                ? round(($selectedLoan->total_interest / $selectedLoan->lending_amount) * 100, 2)
                : 0;

            $lendingStatus = lending_status_tbl::create([
                'lending_id' => $selectedLoan->id,
                'user_id' => $selectedLoan->user_id,
                'remaining_balance' => $selectedLoan->total_payment,
                'total_paid' => 0,
                'payments_made' => 0,
                'total_payments' => $termMonths,
                'interest_rate' => $interestRate,
                'next_due_date' => now()->addMonth()->format('Y-m-d'),
                'status' => 'Active',
            ]);
        }

        $paymentHistory = $selectedLoan
            ? lending_repayments_tbl::where('lending_id', $selectedLoan->id)
                ->orderBy('payment_date', 'desc')->get()
            : collect();

        return view('members_components.loan_status', array_merge(
            ['username' => $username, 'email' => $email],
            compact('loans', 'selectedLoan', 'lendingStatus', 'paymentHistory')
        ));
    }
}