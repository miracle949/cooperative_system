<?php

namespace App\Http\Controllers;

use App\Models\lending_program_tbl;
use App\Models\lending_status_tbl;   // ADD THIS
use App\Models\lending_repayments_tbl;   // ADD THIS
use Illuminate\Http\Request;
use Auth;

class lendingController extends Controller
{
    public function storeRepayment(Request $request)
    {
        $request->validate([
            'lending_id' => 'required|exists:lending_program_tbls,id',
            'amount_paid' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
        ]);

        lending_repayments_tbl::create([
            'lending_id' => $request->lending_id,
            'user_id' => auth()->id(),
            'payment_number' => $request->payment_number,
            'amount_paid' => $request->amount_paid,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'reference_no' => $request->reference_no
                ?: 'RCP-' . now()->format('YmdHis'),
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
                // lending_program_tbl::where('id', $request->lending_id)
                //     ->update(['status' => 'Completed']);
            }
            $status->save();
        }

        return redirect()->route('LoanStatus', ['loan_id' => $request->lending_id])
            ->with('success', 'Payment recorded successfully!');
    }

    public function loanStatus(Request $request)
    {
        $memberId = auth()->id();
        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;

        // Get all approved loans
        $loans = lending_program_tbl::where('user_id', $memberId)
            ->where('status', 'Approved')
            ->get();

        // Default to first loan if no loan_id in URL
        $selectedId = $request->get('loan_id');
        $selectedLoan = $selectedId
            ? lending_program_tbl::where('id', $selectedId)->where('user_id', $memberId)->first()
            : $loans->first(); // AUTO SELECT FIRST LOAN

        // Get lending status
        $lendingStatus = $selectedLoan
            ? lending_status_tbl::where('lending_id', $selectedLoan->id)->first()
            : null;

        // Get payment history
        $paymentHistory = $selectedLoan
            ? lending_repayments_tbl::where('lending_id', $selectedLoan->id)
                ->orderBy('payment_date', 'desc')
                ->get()
            : collect();

        return view('members_components.loan_status', array_merge(
            ['username' => $username,
            "email" => $email],
            compact('loans', 'selectedLoan', 'lendingStatus', 'paymentHistory')
        ));
    }

    public function lendingProgram(Request $request)
    {
        // dd(auth()->id(), auth()->check());
        // dd($request->all());
        try {
            $request->validate([
                "lending_type" => "nullable|string",
                "lending_amount" => "nullable|numeric",
                "lending_type_term" => "nullable|string",
                "monthly_income" => "nullable|numeric",
                "monthly_payment" => "nullable|numeric",
                "total_payment" => "nullable|numeric",
                "total_interest" => "nullable|numeric",
                "purpose_loan" => "nullable|string",
                "valid_id" => "nullable|file|mimes:jpg,jpeg,png,pdf|max:2048",
                "proof_of_income" => "nullable|file|mimes:jpg,jpeg,png,pdf|max:2048",
            ]);

            // Store files and get their paths
            $validIdPath = null;
            $proofOfIncomePath = null;

            if ($request->hasFile('valid_id')) {
                $validIdPath = $request->file('valid_id')->store('documents/valid_id', 'public');
            }

            if ($request->hasFile('proof_of_income')) {
                $proofOfIncomePath = $request->file('proof_of_income')->store('documents/proof_of_income', 'public');
            }

            // Generate reference number and date filed
            $referenceNo = 'LN-' . date('YmdHis') . rand(10, 99);
            $dateFiled = now()->format('M d, Y');

            lending_program_tbl::create([
                "user_id" => auth()->id(),
                "reference_no" => $referenceNo,
                "lending_type" => $request->lending_type,
                "lending_amount" => $request->lending_amount,
                "lending_type_term" => $request->lending_type_term,
                "monthly_income" => $request->monthly_income,
                "monthly_payment" => $request->monthly_payment,
                "total_payment" => $request->total_payment,
                "total_interest" => $request->total_interest,
                "purpose_loan" => $request->purpose_loan,
                "valid_id" => $validIdPath,
                "proof_of_income" => $proofOfIncomePath,
                "status" => "Pending",
            ]);

            return redirect()->route("LoanApplication")
                ->with("ApplySuccess", "Lending Apply Successfully!")
                ->with("ReferenceNo", $referenceNo)
                ->with("DateFiled", $dateFiled);

        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getLine(), $e->getFile());
        }
    }
}