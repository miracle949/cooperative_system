<?php

namespace App\Http\Controllers;

use App\Models\lending_program_tbl;
use App\Models\lending_status_tbl;
use App\Models\lending_repayments_tbl;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\savings_account_tbl;
use App\Models\savings_transaction_tbl;
use Auth;

class lendingController extends Controller
{

    const PAYMENT_INTERVAL_DAYS = 5;
    // ─── Shared helper ────────────────────────────────────────────────────────────
    private function getLoanPageData(): array
    {
        $memberId = auth()->id();

        $savedMonthlyIncome = DB::table('lending_program_tbls')
            ->where('user_id', $memberId)
            ->whereNotNull('monthly_income')
            ->orderBy('created_at', 'desc')
            ->value('monthly_income');

        $savingsAccount = DB::table('savings_account_tbls')
            ->where('user_id', $memberId)->first();

        $currentSavings = (float) ($savingsAccount->balance ?? 0);

        $savingsToLoanRatio = 0.6;   // 3,000 : 5,000
        $minSavingsToApply = 3000;  // floor needed just to unlock the form

        $canApplyLoan = $currentSavings >= $minSavingsToApply;
        $maxLoan = 25000;

        // Savings caps total borrowing capacity, regardless of the ₱25,000 program ceiling
        $maxLoanBySavings = $savingsToLoanRatio > 0
            ? floor(($currentSavings / $savingsToLoanRatio) / 100) * 100
            : 0;

        // Get all active loans (Pending, Approved, Completed) and calculate remaining balance
        // Using lending_status_tbl to get actual remaining balance instead of original loan amount
        $loans = DB::table('lending_program_tbls')
            ->where('user_id', $memberId)
            ->whereIn('status', ['Pending', 'Approved', 'Completed'])
            ->get();

        // Count only the principal (lending_amount) - the actual loan amount borrowed, excluding interest
        $totalActiveLoan = 0;
        $totalPaidOnActiveLoans = 0;

        $approvedLoans = DB::table('lending_program_tbls as l')
            ->leftJoin('lending_status_tbls as s', 's.lending_id', '=', 'l.id')
            ->where('l.user_id', $memberId)
            ->where('l.status', 'Approved')
            ->select('l.*', 's.due_date', 's.remaining_balance', 's.status as loan_status')
            ->get();

        $today = now()->timezone('Asia/Manila')->toDateString();
        $weekEnd = now()->timezone('Asia/Manila')->addDays(7)->toDateString();

        $dueTodayCount = $approvedLoans->filter(
            fn($l) =>
            $l->due_date && $l->due_date === $today && ($l->remaining_balance ?? 0) > 0
        )->count();

        $dueThisWeekCount = $approvedLoans->filter(
            fn($l) =>
            $l->due_date && $l->due_date > $today && $l->due_date <= $weekEnd && ($l->remaining_balance ?? 0) > 0
        )->count();

        $overdueCount = $approvedLoans->filter(
            fn($l) =>
            $l->due_date && $l->due_date < $today && ($l->remaining_balance ?? 0) > 0
        )->count();

        // All loans (any status) for the table
        $allLoans = DB::table('lending_program_tbls as l')
            ->leftJoin('lending_status_tbls as s', 's.lending_id', '=', 'l.id')
            ->where('l.user_id', $memberId)
            ->orderBy('l.created_at', 'asc')   // ← was 'desc'
            ->select(
                'l.*',
                's.due_date',
                's.remaining_balance',
                's.total_paid',
                's.payments_made',
                's.total_payments'
            )
            ->get()
            ->map(function ($loan) use ($today, $weekEnd) {
                $typeMap = [
                    'Personal Lending' => 'Personal Loan',
                    'Emergency Lending' => 'Emergency Loan',
                    'Business Lending' => 'Business Loan',
                    'Education Lending' => 'Education Loan',
                ];
                $loan->lending_type = $typeMap[$loan->lending_type] ?? $loan->lending_type;

                $totalPayments = (int) ($loan->total_payments ?? 0);
                $paymentsMade = (int) ($loan->payments_made ?? 0);

                $loan->total_payments = $totalPayments;
                $loan->payments_made = $paymentsMade;
                $loan->progress_percent = $totalPayments > 0
                    ? min(100, round(($paymentsMade / $totalPayments) * 100))
                    : 0;

                // Per-installment amount
                $loan->monthly_payment = $totalPayments > 0
                    ? round((float) ($loan->total_payment ?? $loan->lending_amount) / $totalPayments, 2)
                    : 0;

                // Due tag (today / week / overdue) — only for active balances
                $loan->due_category = null;
                if ($loan->status === 'Approved' && $loan->due_date && ($loan->remaining_balance ?? 0) > 0) {
                    if ($loan->due_date === $today) {
                        $loan->due_category = 'today';
                    } elseif ($loan->due_date > $today && $loan->due_date <= $weekEnd) {
                        $loan->due_category = 'week';
                    } elseif ($loan->due_date < $today) {
                        $loan->due_category = 'overdue';
                    }
                }

                return $loan;
            });

        $allLoansCount = $allLoans->count();

        foreach ($loans as $loan) {
            // Skip Completed loans - they're fully paid
            if ($loan->status === 'Completed') {
                continue;
            }

            // Use lending_amount (principal only) - not total_payment which includes interest
            $principal = (float) $loan->lending_amount;

            $status = DB::table('lending_status_tbls')
                ->where('lending_id', $loan->id)
                ->first();

            if ($status && $principal > 0) {
                $totalPaid = isset($status->total_paid) ? (float) $status->total_paid : 0;

                // Get total payment (principal + interest)
                $totalPayment = isset($loan->total_payment) ? (float) $loan->total_payment : $principal;
                $remainingBalance = isset($status->remaining_balance) ? (float) $status->remaining_balance : $principal;

                // Calculate remaining principal proportionally
                // remaining_balance/total_payment = remaining_principal/principal
                if ($totalPayment > 0 && $remainingBalance > 0) {
                    $principalRemaining = ($remainingBalance / $totalPayment) * $principal;
                } else {
                    $principalRemaining = $principal;
                }

                // Cap at original principal
                $principalRemaining = min($principal, max(0, $principalRemaining));

                if ($principalRemaining > 0.01) {
                    $totalActiveLoan += $principalRemaining;
                }
                $totalPaidOnActiveLoans += $totalPaid;
            } else {
                // No status yet - use full principal as active
                if ($principal > 0) {
                    $totalActiveLoan += $principal;
                }
            }
        }

        $effectiveCeiling = min($maxLoan, $maxLoanBySavings);
        $remainingLoanable = max(0, $effectiveCeiling - $totalActiveLoan);

        $remainingLoanableCents = (int) round($remainingLoanable * 100);
        $effectiveCeilingCents = (int) round($effectiveCeiling * 100);

        if ($remainingLoanableCents >= $effectiveCeilingCents - 100 || $totalActiveLoan < 0.02) {
            $remainingLoanable = $effectiveCeiling;
        } else {
            $remainingLoanable = $remainingLoanableCents / 100;
        }

        $hasFullyLoaned = $totalActiveLoan >= $effectiveCeiling;

        // Due today loans (full records)
        $dueTodayLoans = $approvedLoans->filter(
            fn($l) =>
            $l->due_date && $l->due_date === $today && ($l->remaining_balance ?? 0) > 0
        )->values();

        // Due this week loans (full records)
        $dueThisWeekLoans = $approvedLoans->filter(
            fn($l) =>
            $l->due_date && $l->due_date > $today && $l->due_date <= $weekEnd && ($l->remaining_balance ?? 0) > 0
        )->values();

        // Overdue loans (full records)
        $overdueLoans = $approvedLoans->filter(
            fn($l) =>
            $l->due_date && $l->due_date < $today && ($l->remaining_balance ?? 0) > 0
        )->values();

        return compact(
            'currentSavings',
            'maxLoanBySavings',
            'minSavingsToApply',
            'canApplyLoan',
            'totalActiveLoan',
            'remainingLoanable',
            'hasFullyLoaned',
            'totalPaidOnActiveLoans',
            'savedMonthlyIncome',
            // ── new ──
            'allLoans',
            'allLoansCount',
            'dueTodayCount',
            'dueThisWeekCount',
            'overdueCount',
            'dueTodayLoans',
            'dueThisWeekLoans',
            'overdueLoans'
        );
    }

    // ─── GET: Loan Application page ───────────────────────────────────────────────
    public function index()
    {
        // $this->autoProcessOverdueLoans();
        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;

        // Pull the FULL settings row per loan type, not just interest_rate
        $dbSettings = DB::table('loan_settings_tbls')
            ->where('is_active', true)
            ->get()
            ->keyBy('loan_type'); // keyed by 'Personal Loan', 'Business Loan', etc.

        $typeMap = [
            'Personal Lending' => 'Personal Loan',
            'Emergency Lending' => 'Emergency Loan',
            'Business Lending' => 'Business Loan',
            'Education Lending' => 'Education Loan',
        ];

        $loanSettings = [];
        foreach ($typeMap as $formType => $dbType) {
            $s = $dbSettings[$dbType] ?? null;

            // Key by $dbType — this MUST match the <option value="..."> used
            // in $mOptData() inside the blade (it calls $mOptData('Personal Loan'), etc.)
            $loanSettings[$dbType] = [
                'interest_rate' => $s ? ((float) $s->interest_rate / 100) : 0.02,
                'processing_fee_rate' => $s->processing_fee_rate ?? 0,
                'service_fee_rate' => $s->service_fee_rate ?? 0,
                'loan_protection_fee' => $s->loan_protection_fee ?? 0,
                'retention_unpaid_rate' => $s->retention_unpaid_rate ?? 0,
            ];
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
            ->whereIn('type', ['Deposit', 'Subscription'])
            ->whereIn('status', ['Completed', 'completed'])
            ->sum('shares') ?? 0;

        $withdrawals = DB::table('share_capital_transaction_tbls')
            ->where('share_capital_account_id', $account->id ?? 0)
            ->where('type', 'Withdrawal')
            ->whereIn('status', ['Approved', 'approved'])
            ->sum('shares') ?? 0;

        $currentShares = $deposits - $withdrawals;

        $savingsAccount = DB::table('savings_account_tbls')->where('user_id', $memberId)->first();
        $currentSavings = (float) ($savingsAccount->balance ?? 0);

        $savingsToLoanRatio = 0.6;
        $minSavingsToApply = 3000;

        if ($currentSavings < $minSavingsToApply) {
            return redirect()->back()->with(
                'loan_blocked',
                'You must have at least ₱' . number_format($minSavingsToApply, 2) . ' in Savings before applying for a loan. ' .
                'You currently have ₱' . number_format($currentSavings, 2) . '.'
            );
        }

        // Use remaining balance from lending_status_tbl instead of original loan amount
        $loans = DB::table('lending_program_tbls')
            ->where('user_id', $memberId)
            ->whereIn('status', ['Pending', 'Approved', 'Completed'])
            ->get();

        // Count only principal (lending_amount), excluding interest
        $totalActiveLoan = 0;

        foreach ($loans as $loan) {
            if ($loan->status === 'Completed') {
                continue;
            }

            $principal = (float) $loan->lending_amount;
            $status = DB::table('lending_status_tbls')
                ->where('lending_id', $loan->id)
                ->first();

            if ($status && $principal > 0) {
                $totalPayment = isset($loan->total_payment) ? (float) $loan->total_payment : $principal;
                $remainingBalance = isset($status->remaining_balance) ? (float) $status->remaining_balance : $principal;

                if ($totalPayment > 0 && $remainingBalance > 0) {
                    $principalRemaining = ($remainingBalance / $totalPayment) * $principal;
                } else {
                    $principalRemaining = $principal;
                }
                $principalRemaining = min($principal, max(0, $principalRemaining));

                if ($principalRemaining > 0.01) {
                    $totalActiveLoan += $principalRemaining;
                }
            } else {
                if ($principal > 0) {
                    $totalActiveLoan += $principal;
                }
            }
        }

        $remainingLoanable = max(0, $maxLoan - $totalActiveLoan);
        $remainingLoanableCents = (int) round($remainingLoanable * 100);

        if ($remainingLoanableCents >= 2499900 || $totalActiveLoan < 0.02) {
            $remainingLoanable = 25000.00;
        } else {
            $remainingLoanable = $remainingLoanableCents / 100;
        }

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

        // Use cents comparison to avoid floating point issues
        $requestedCents = (int) ($request->lending_amount * 100);
        $maxLoanCents = (int) ($maxLoan * 100);

        if ($requestedCents > $maxLoanCents) {
            return redirect()->back()
                ->with('loan_blocked', 'The maximum loan amount allowed is ₱25,000.')
                ->withInput();
        }

        // ── Loan term validation per lending type ────────────────────────────────
        $lendingType = $request->lending_type;
        $allowedTerms = match ($lendingType) {
            'Business Loan' => ['6 months', '12 months'],
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

        // ── Fetch fee settings for this loan type ─────────────────────────────────
        $settings = DB::table('loan_settings_tbls')
            ->where('loan_type', $lendingType)
            ->where('is_active', true)
            ->first();

        if (!$settings) {
            return redirect()->back()
                ->with('loan_blocked', 'Loan settings are not configured for this loan type. Please contact the admin.')
                ->withInput();
        }

        try {
            $request->validate([
                'lending_type' => 'nullable|string',
                'lending_amount' => 'nullable|numeric',
                'lending_type_term' => 'nullable|string',
                'monthly_income' => 'nullable|numeric',
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

            $validIdField = match ($lendingType) {
                'Personal Loan' => 'personal_valid_id',
                'Emergency Loan' => 'emergency_valid_id',
                'Business Loan' => 'business_valid_id',
                'Education Loan' => 'education_valid_id',
                default => null,
            };

            $proofOfIncomeField = match ($lendingType) {
                'Personal Loan' => 'personal_proof_of_income',
                'Emergency Loan' => 'emergency_proof_of_income',
                'Business Loan' => 'business_proof_of_income',
                default => null,
            };

            $referenceNo = 'LN-' . date('YmdHis') . rand(10, 99);

            // ── Compute fees from loan_settings_tbls (Section III. Loan Charges) ──
            $principal = (float) $request->lending_amount;
            $termMonths = (int) filter_var($request->lending_type_term, FILTER_SANITIZE_NUMBER_INT);

            // a. Processing & Collection fee = 2%
            $processingFee = round($principal * ($settings->processing_fee_rate / 100), 2);

            // b. Service and Legal fee = 2%
            $serviceFee = round($principal * ($settings->service_fee_rate / 100), 2);

            // c. Loan Protection Plan = ₱2 per month of term
            $loanProtectionFee = round($settings->loan_protection_fee * $termMonths, 2);

            // e. Retention/CBU = 3% fully paid / 6% not fully paid
            // TODO: wire up real "fully paid subscription" check.
            // Defaulting to the unpaid rate (6%) for everyone until that logic exists.
            $retentionRateApplied = $settings->retention_unpaid_rate;
            $retentionAmount = round($principal * ($retentionRateApplied / 100), 2);

            // Net proceeds = amount actually released to borrower
            $netProceeds = round($principal - $processingFee - $serviceFee - $loanProtectionFee - $retentionAmount, 2);

            // d. Interest rate = 2%/mo diminishing balance
            $monthlyRate = $settings->interest_rate / 100;
            $principalPerMonth = $termMonths > 0 ? $principal / $termMonths : 0;
            $totalInterest = 0;

            for ($i = 0; $i < $termMonths; $i++) {
                $remainingBalance = $principal - ($principalPerMonth * $i);
                $totalInterest += $remainingBalance * $monthlyRate;
            }
            $totalInterest = round($totalInterest, 2);

            $totalPayment = round($principal + $totalInterest, 2);
            $monthlyPayment = $termMonths > 0 ? round($totalPayment / $termMonths, 2) : 0;

            lending_program_tbl::create([
                'user_id' => $memberId,
                'reference_no' => $referenceNo,
                'lending_type' => $lendingType,
                'lending_amount' => $principal,
                'lending_type_term' => $request->lending_type_term,
                'due_date' => now()->timezone('Asia/Manila')->addMonths($termMonths)->format('Y-m-d'), // ← add this

                // Computed fees — keys must match the actual columns
                'processing_fee_rate' => $processingFee,
                'service_fee_rate' => $serviceFee,
                'loan_protection_fee' => $loanProtectionFee,
                'retention_paid_rate' => $retentionRateApplied == $settings->retention_paid_rate ? $retentionAmount : 0,
                'retention_unpaid_rate' => $retentionRateApplied == $settings->retention_unpaid_rate ? $retentionAmount : 0,
                'net_proceeds' => $netProceeds,

                'monthly_income' => $request->monthly_income,
                'monthly_payment' => $monthlyPayment,
                'total_payment' => $totalPayment,
                'total_interest' => $totalInterest,

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

            AuditLog::log(
                'Member Loan Application',
                "Applied for {$lendingType} loan of ₱{$request->lending_amount} (Ref: {$referenceNo})",
                'loan',
                lending_program_tbl::where('reference_no', $referenceNo)->first()?->id
            );

            return redirect()->route('LoanApplication')
                ->with('ApplySuccess', true)
                ->with('ReferenceNo', $referenceNo)
                ->with('DateFiled', now()->timezone('Asia/Manila')->format('M d, Y · h:i A'))
                ->with('MemberName', trim(Auth::user()->first_name . ' ' . Auth::user()->last_name) ?: Auth::user()->username);

        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getLine(), $e->getFile());
        }
    }

    public function adminApplyOverduePenalties(Request $request, \App\Services\LoanPenaltyService $service)
    {
        $results = $service->applyPenaltiesForAllOverdueLoans(force: (bool) $request->boolean('force'));

        return response()->json([
            'success' => true,
            'message' => 'Applied penalties to ' . count($results) . ' overdue loan(s).',
            'penalties' => $results,
        ]);
    }

    // ─── Repayment ────────────────────────────────────────────────────────────────
    // ─── Repayment ────────────────────────────────────────────────────────────────
    // ─── Repayment ────────────────────────────────────────────────────────────────
    public function storeRepayment(Request $request)
    {
        $request->validate([
            'lending_id' => 'required|exists:lending_program_tbls,id',
            'amount_paid' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'payment_type' => 'nullable|in:monthly,full',
            'gcash_proof' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $proofPath = $request->hasFile('gcash_proof')
            ? $request->file('gcash_proof')->store('documents/gcash_proofs', 'public')
            : null;

        $loan = lending_program_tbl::findOrFail($request->lending_id);
        $status = lending_status_tbl::where('lending_id', $request->lending_id)->first();
        $paymentType = $request->get('payment_type', 'monthly');

        // Compute the per-installment amount directly — $loan has no
        // monthly_payment column/accessor of its own.
        $totalPayment = (float) ($loan->total_payment ?? $loan->lending_amount);
        $totalPayments = (int) ($status->total_payments ?? 0);
        $monthlyPayment = $totalPayments > 0 ? round($totalPayment / $totalPayments, 2) : (float) $request->amount_paid;

        // Tracks the note + raw amount for whatever penalty gets applied on
        // this visit, so both are saved onto the repayment record itself —
        // instead of only existing as an aggregate on lending_status_tbls.
        $penaltyNote = null;
        $penaltyAmountForRecord = 0;

        // ── 2% Overdue Penalty ──────────────────────────────────────────────────
        // Applied directly to the loan (not savings) once per missed due date.
        // Guarded by last_penalty_date so repeat visits/payments on the same
        // overdue installment don't stack the penalty multiple times.
        if ($status) {
            // IMPORTANT: anchor off the authoritative due_date stored on
            // lending_status_tbls — the SAME source of truth loanStatus() uses
            // to build the schedule/hero display. Previously this recomputed
            // the due date independently from $loan->created_at + a fixed
            // interval, which can drift from the real due_date (e.g. after a
            // prior penalty/advance) and silently disagree with what the UI
            // already showed as overdue — causing the penalty to never
            // actually get saved even when the page said it should apply.
            $nextDueDate = $status->due_date
                ? \Carbon\Carbon::parse($status->due_date)
                : \Carbon\Carbon::parse($loan->created_at)
                    ->addDays(((int) $status->payments_made + 1) * self::PAYMENT_INTERVAL_DAYS);

            $isOverdue = $nextDueDate->lt(now()->timezone('Asia/Manila'));

            $alreadyPenalized = $status->last_penalty_date
                && \Carbon\Carbon::parse($status->last_penalty_date)->gte($nextDueDate);

            if ($isOverdue && !$alreadyPenalized) {
                $penaltyAmount = round($monthlyPayment * 0.02, 2);

                $status->penalty_amount = (float) ($status->penalty_amount ?? 0) + $penaltyAmount;
                $status->remaining_balance = (float) $status->remaining_balance + $penaltyAmount;
                $status->last_penalty_date = $nextDueDate->format('Y-m-d');
                $status->save();

                $penaltyNote = "₱" . number_format($penaltyAmount, 2) . " overdue penalty applied (installment due {$nextDueDate->format('M d, Y')})";
                $penaltyAmountForRecord = $penaltyAmount;

                AuditLog::log(
                    'Loan Overdue Penalty',
                    "Applied 2% overdue penalty of ₱{$penaltyAmount} on loan (ID: {$request->lending_id})",
                    'loan',
                    $request->lending_id
                );
            }
        }

        // Merge any member-entered notes with the auto-generated penalty note
        // so both are preserved and visible on the repayment record.
        $combinedNotes = trim(implode(' — ', array_filter([$request->notes, $penaltyNote])));
        // Compute income breakdown per payment
        $interestRatio = ($loan->total_payment > 0) ? ($loan->total_interest / $loan->total_payment) : 0;

        if ($paymentType === 'full' && $status) {
            // ── FULL REPAYMENT (Cash) ────────────────────────────────────────
            $remainingPayments = $status->total_payments - $status->payments_made;

            for ($i = 1; $i <= $remainingPayments; $i++) {
                $paymentsMade = lending_repayments_tbl::where('lending_id', $request->lending_id)->count();
                $installmentAmount = $monthlyPayment;
                $interestPaid = round($installmentAmount * $interestRatio, 2);
                $principalPaid = round($installmentAmount - $interestPaid, 2);

                lending_repayments_tbl::create([
                    'lending_id' => $request->lending_id,
                    'user_id' => auth()->id(),
                    'payment_number' => $paymentsMade + 1,
                    'amount_due' => $monthlyPayment,
                    'amount_paid' => $monthlyPayment,
                    'late_fee' => $i === 1 ? $penaltyAmountForRecord : 0,
                    'penalty_applied_at' => $i === 1 && $penaltyAmountForRecord > 0 ? now()->timezone('Asia/Manila') : null,
                    'payment_proof_path' => $i === 1 ? $proofPath : null,
                    'principal_paid' => $principalPaid,
                    'interest_paid' => $interestPaid,
                    'service_fee_paid' => 0,
                    'due_date' => $status->due_date ?? now()->format('Y-m-d'),
                    'payment_date' => now()->format('Y-m-d'),
                    'payment_method' => $request->payment_method,
                    'payment_type' => $paymentType,
                    'reference_no' => $request->reference_no ?: 'RCP-FULL-' . now()->format('YmdHis') . '-' . $i,
                    'notes' => $i === 1 ? ($combinedNotes ?: 'Full balance repayment') : 'Full balance repayment',
                    'recorded_by' => null,
                ]);
            }

            // Mark loan as fully paid
            $status->total_paid += $request->amount_paid;
            $status->remaining_balance = 0;
            $status->payments_made = $status->total_payments;
            $status->status = 'Completed';
            $status->save();

            lending_program_tbl::where('id', $request->lending_id)
                ->update(['status' => 'Completed']);

        } else {
            // ── SINGLE MONTHLY PAYMENT ───────────────────────────────────────
            $interestPaid = round($request->amount_paid * $interestRatio, 2);
            $principalPaid = round($request->amount_paid - $interestPaid, 2);

            lending_repayments_tbl::create([
                'lending_id' => $request->lending_id,
                'user_id' => auth()->id(),
                'payment_number' => $request->payment_number,
                'amount_due' => $monthlyPayment,
                'amount_paid' => $request->amount_paid,
                'late_fee' => $penaltyAmountForRecord,
                'penalty_applied_at' => $penaltyAmountForRecord > 0 ? now()->timezone('Asia/Manila') : null,
                'payment_proof_path' => $proofPath,
                'principal_paid' => $principalPaid,
                'interest_paid' => $interestPaid,
                'service_fee_paid' => 0,
                'due_date' => $status->due_date ?? now()->format('Y-m-d'),
                'payment_date' => now()->format('Y-m-d'),
                'payment_method' => $request->payment_method,
                'payment_type' => $paymentType,
                'reference_no' => $request->reference_no ?: 'RCP-' . now()->format('YmdHis'),
                'notes' => $combinedNotes ?: null,
                'recorded_by' => null,
            ]);

            if ($status) {
                $status->total_paid += $request->amount_paid;
                $status->remaining_balance = max(0, $status->remaining_balance - $request->amount_paid);
                $status->payments_made += 1;

                if ($status->remaining_balance <= 0 || $status->payments_made >= $status->total_payments) {
                    $status->status = 'Completed';
                    $status->payments_made = $status->total_payments;

                    lending_program_tbl::where('id', $request->lending_id)
                        ->update(['status' => 'Completed']);
                } else {
                    // Advance to the next unpaid installment's due date, anchored
                    // to when the loan was originally created — not "now".
                    $status->due_date = \Carbon\Carbon::parse($loan->created_at)
                        ->addDays(($status->payments_made + 1) * self::PAYMENT_INTERVAL_DAYS)
                        ->format('Y-m-d');
                }

                $status->save();
            }
        }

        $paymentTypeLabel = $paymentType === 'full' ? 'Full repayment' : 'Monthly payment';
        AuditLog::log(
            'Loan Repayment',
            "{$paymentTypeLabel} of ₱{$request->amount_paid} on loan (ID: {$request->lending_id})",
            'loan',
            $request->lending_id
        );

        return redirect()->route('LoanStatus', ['loan_id' => $request->lending_id])
            ->with('success', 'Payment recorded successfully!');
    }

    // ─── Loan Status page ─────────────────────────────────────────────────────────
    // ─── Loan Status page ─────────────────────────────────────────────────────────
    public function loanStatus(Request $request)
    {
        // $this->autoProcessOverdueLoans();
        $memberId = auth()->id();
        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;

        $typeMap = [
            'Personal Lending' => 'Personal Loan',
            'Emergency Lending' => 'Emergency Loan',
            'Business Lending' => 'Business Loan',
            'Education Lending' => 'Education Loan',
        ];

        $today = now()->timezone('Asia/Manila')->toDateString();

        // Show Approved AND Completed loans so members can browse everything,
// including fully paid loans, from this one grid.
        $loans = DB::table('lending_program_tbls as l')
            ->leftJoin('lending_status_tbls as s', 's.lending_id', '=', 'l.id')
            ->where('l.user_id', $memberId)
            ->whereIn('l.status', ['Approved', 'Completed'])
            ->orderBy('l.created_at', 'desc')
            ->select('l.*', 's.remaining_balance', 's.payments_made', 's.total_payments', 's.due_date')
            ->get()
            ->map(function ($loan) use ($typeMap, $today) {
                $loan->display_type = $typeMap[$loan->lending_type] ?? $loan->lending_type;

                if ($loan->status === 'Completed') {
                    $loan->card_status = 'Completed';
                } elseif ($loan->due_date && $loan->due_date < $today && ($loan->remaining_balance ?? 0) > 0) {
                    $loan->card_status = 'Overdue';
                } else {
                    $loan->card_status = 'Active';
                }

                $totalPayments = (int) ($loan->total_payments ?? 0);
                $paymentsMade = (int) ($loan->payments_made ?? 0);
                $loan->progress_percent = $totalPayments > 0
                    ? min(100, round(($paymentsMade / $totalPayments) * 100))
                    : 0;

                return $loan;
            });

        // No auto-select — leave $selectedLoan null unless the URL explicitly
// carries ?loan_id=, so the grid shows by default.
        $selectedId = $request->get('loan_id');

        $selectedLoan = $selectedId
            ? lending_program_tbl::where('id', $selectedId)->where('user_id', $memberId)->first()
            : null;

        if ($selectedLoan) {
            $selectedLoan->display_type = $typeMap[$selectedLoan->lending_type] ?? $selectedLoan->lending_type;
        }

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
                'due_date' => \Carbon\Carbon::parse($selectedLoan->created_at)->addDays(self::PAYMENT_INTERVAL_DAYS)->format('Y-m-d'),
                'status' => 'Active',
            ]);

            AuditLog::log(
                'Loan Status Initialized',
                "Initialized lending status for loan #{$selectedLoan->id} (auto-created on status view)",
                'loan_status',
                $lendingStatus->id
            );
        }

        $paymentHistory = $selectedLoan
            ? lending_repayments_tbl::where('lending_id', $selectedLoan->id)
                ->orderBy('payment_date', 'desc')->get()
            : collect();

        // Map installment # → the actual late fee charged on that payment,
        // pulled straight from the real repayment record (not the aggregate
        // on lending_status_tbls) so the Payment Schedule can show exactly
        // which installment the penalty was applied to.
        $penaltyByInstallment = $paymentHistory
            ->filter(fn($p) => ($p->late_fee ?? 0) > 0)
            ->keyBy('payment_number');

        // ── Build computed hero/breakdown data ──────────────────────────────────
        $paymentSchedule = collect();
        $progressPercent = 0;
        $remainingPrincipal = 0;
        $monthlyDue = 0;

        $nextDueDate = null;          // earliest unpaid installment overall (may be overdue)
        // — drives status pill + penalty logic only.
        $displayNextDueDate = null;   // earliest unpaid installment that is NOT overdue —
        // this is what the "Next Due" hero box shows. "Next Due"
        // means the next thing coming up, so it should never
        // silently become an already-missed date.
        $daysAway = null;
        $displayDaysAway = null;

        $overdueDate = null;             // earliest unpaid installment that IS overdue, if any.
        $overdueDaysCount = null;        // how many days overdue (positive int).
        $overdueInstallmentNumber = null; // which installment # is overdue.
        $currentOverduePenalty = 0;      // penalty tied SPECIFICALLY to the overdue
        // installment above — 0 whenever nothing is
        // actually overdue right now. Used for the
        // repayment modal prefill so it never adds a
        // stale historical penalty to an Upcoming
        // installment's payment. Deliberately separate
        // from $penaltyAmount below, which is a lifetime
        // running total and should NOT be used for that.

        $fullBalanceRemaining = 0;
        $monthsRemaining = 0;
        $serviceFee = 0;
        $interestRate = 0;
        $totalInterest = 0;
        $processingFee = 0;
        $loanProtectionFee = 0;
        $retentionFee = 0;
        $netProceeds = 0;
        $penaltyAmount = 0;
        $loanStatusLabel = 'Active';

        if ($selectedLoan && $lendingStatus) {
            $principal = (float) $selectedLoan->lending_amount;
            $totalPayments = (int) $lendingStatus->total_payments;
            $paymentsMade = (int) $lendingStatus->payments_made;
            $totalPayment = (float) ($selectedLoan->total_payment ?? $principal);
            $monthlyDue = $totalPayments > 0 ? round($totalPayment / $totalPayments, 2) : 0;

            $progressPercent = $totalPayments > 0
                ? min(100, round(($paymentsMade / $totalPayments) * 100, 2))
                : 0;

            $totalInterest = (float) ($selectedLoan->total_interest ?? 0);
            $interestRate = (float) ($lendingStatus->interest_rate ?? 0);

            $processingFee = (float) ($selectedLoan->processing_fee_rate ?? 0);
            $serviceFee = (float) ($selectedLoan->service_fee_rate ?? 0);
            $loanProtectionFee = (float) ($selectedLoan->loan_protection_fee ?? 0);
            $retentionFee = (float) ($selectedLoan->retention_paid_rate ?? 0)
                + (float) ($selectedLoan->retention_unpaid_rate ?? 0);
            $netProceeds = (float) ($selectedLoan->net_proceeds ?? 0);
            $penaltyAmount = (float) ($lendingStatus->penalty_amount ?? 0);

            $remainingBalance = (float) $lendingStatus->remaining_balance;
            if ($totalPayment > 0 && $remainingBalance > 0) {
                $remainingPrincipal = round(($remainingBalance / $totalPayment) * $principal, 2);
            } else {
                $remainingPrincipal = 0;
            }

            $fullBalanceRemaining = $remainingBalance;
            $monthsRemaining = max(0, $totalPayments - $paymentsMade);

            // Build amortization / payment schedule, anchored on the authoritative
            // due_date stored in lending_status_tbls.
            $startDate = \Carbon\Carbon::parse($selectedLoan->created_at);
            $today = now()->timezone('Asia/Manila');

            $nextInstallmentNumber = $paymentsMade + 1;
            $anchorDueDate = $lendingStatus->due_date
                ? \Carbon\Carbon::parse($lendingStatus->due_date)
                : $startDate->copy()->addDays($nextInstallmentNumber * self::PAYMENT_INTERVAL_DAYS);

            for ($i = 1; $i <= $totalPayments; $i++) {
                $dueDateForRow = $anchorDueDate->copy()
                    ->addDays(($i - $nextInstallmentNumber) * self::PAYMENT_INTERVAL_DAYS);

                $isPaid = $i <= $paymentsMade;
                $isOverdue = !$isPaid && $dueDateForRow->lt($today);
                $isNext = !$isPaid && !$nextDueDate;

                // Actual charged penalty (from a real repayment record), if any.
                $rowPenalty = $penaltyByInstallment[$i]->late_fee ?? 0;

                // If this row is overdue but hasn't actually been charged yet (no
// repayment record), fall back to the live 2% preview — same math
// used for $currentOverduePenalty — so the member sees the real
// amount they'll owe, not just the base installment.
                if ($isOverdue && $rowPenalty == 0) {
                    $alreadyPenalizedForRow = $lendingStatus->last_penalty_date
                        && \Carbon\Carbon::parse($lendingStatus->last_penalty_date)->gte($dueDateForRow);

                    if (!$alreadyPenalizedForRow) {
                        $rowPenalty = round($monthlyDue * 0.02, 2);
                    }
                }

                $paymentSchedule->push([
                    'number' => $i,
                    'date' => $dueDateForRow->format('M d, Y'),
                    'amount' => $monthlyDue,
                    'paid' => $isPaid,
                    'overdue' => $isOverdue,
                    'is_next' => $isNext,
                    'penalty' => $rowPenalty,
                ]);

                // Earliest unpaid installment overall (may be overdue) — drives
                // the hero status pill + penalty logic. NOT what "Next Due" shows.
                if (!$isPaid && !$nextDueDate) {
                    $nextDueDate = $dueDateForRow;
                }

                // Earliest unpaid installment that is NOT overdue — this is what
                // the "Next Due" hero box actually displays to the member.
                if (!$isPaid && !$isOverdue && !$displayNextDueDate) {
                    $displayNextDueDate = $dueDateForRow;
                }

                // Earliest unpaid installment that IS overdue — surfaced as its
                // own separate note, so it never gets confused with "Next Due".
                if ($isOverdue && !$overdueDate) {
                    $overdueDate = $dueDateForRow;
                    $overdueInstallmentNumber = $i;
                    // Compare start-of-day to start-of-day so a fractional
                    // time-of-day on $today (from now()) doesn't leak into the
                    // count as a decimal, e.g. "2.5249774647801 days overdue".
                    $overdueDaysCount = (int) $dueDateForRow->copy()->startOfDay()
                        ->diffInDays($today->copy()->startOfDay());

                    // Only count this as an outstanding penalty if it hasn't
                    // already been applied for THIS specific due date — this
                    // is what makes $currentOverduePenalty different from the
                    // lifetime $penaltyAmount total below.
                    $alreadyPenalizedForThisOne = $lendingStatus->last_penalty_date
                        && \Carbon\Carbon::parse($lendingStatus->last_penalty_date)->gte($dueDateForRow);

                    if (!$alreadyPenalizedForThisOne) {
                        $currentOverduePenalty = round($monthlyDue * 0.02, 2);
                    }
                }
            }

            if ($nextDueDate) {
                $daysAway = number_format($today->diffInDays($nextDueDate, false));
            }

            if ($displayNextDueDate) {
                $displayDaysAway = number_format($today->diffInDays($displayNextDueDate, false));
            }
            // If everything unpaid is overdue (no future installment yet),
            // $displayNextDueDate stays null and the box falls back to "—".

            // Real hero status: Completed / Overdue / Active
            if ($selectedLoan->status === 'Completed') {
                $loanStatusLabel = 'Completed';
            } elseif ($nextDueDate && $nextDueDate->lt($today)) {
                $loanStatusLabel = 'Overdue';
            } else {
                $loanStatusLabel = 'Active';
            }

            // ── Live penalty preview ──────────────────────────────────────────
            // penalty_amount in the DB only gets written once a payment is
            // actually submitted (see storeRepayment()). So a loan can be
            // visibly overdue here while penalty_amount is still 0. Show what
            // the 2% penalty WOULD be right now, without persisting anything —
            // the real charge still only gets written when the member pays.
            // This still keys off $nextDueDate (the actual overdue installment),
            // NOT $displayNextDueDate, so the amount always matches whichever
            // installment is really overdue — independent of what "Next Due" shows.
            if ($nextDueDate && $nextDueDate->lt($today)) {
                $alreadyPenalizedForThis = $lendingStatus->last_penalty_date
                    && \Carbon\Carbon::parse($lendingStatus->last_penalty_date)->gte($nextDueDate);

                if (!$alreadyPenalizedForThis) {
                    $penaltyAmount += round($monthlyDue * 0.02, 2);
                }
            }
        }

        // The QR the admin uploaded in Settings → Payment Methods Management
        $gcashPaymentMethod = \App\Models\PaymentMethod::where('method_name', 'GCash')
            ->where('is_active', true)
            ->first();

        return view('members_components.loan_status', array_merge(
            ['username' => $username, 'email' => $email],
            compact(
                'loans',
                'selectedLoan',
                'lendingStatus',
                'paymentHistory',
                'paymentSchedule',
                'progressPercent',
                'remainingPrincipal',
                'monthlyDue',
                'nextDueDate',
                'daysAway',
                'displayNextDueDate',
                'displayDaysAway',
                'overdueDate',
                'overdueDaysCount',
                'overdueInstallmentNumber',
                'currentOverduePenalty',
                'fullBalanceRemaining',
                'monthsRemaining',
                'serviceFee',
                'interestRate',
                'totalInterest',
                'processingFee',
                'loanProtectionFee',
                'retentionFee',
                'netProceeds',
                'penaltyAmount',
                'loanStatusLabel',
                'gcashPaymentMethod'
            )
        ));
    }
}