<?php

namespace App\Http\Controllers;

use App\Models\lending_program_tbl;
use Illuminate\Http\Request;

class lendingController extends Controller
{
    public function lendingProgram(Request $request)
    {
        // dd(auth()->id(), auth()->check());
        // dd($request->all());
        try {
            $request->validate([
                "lending_type"      => "nullable|string",
                "lending_amount"    => "nullable|numeric",
                "lending_type_term" => "nullable|string",
                "monthly_income"    => "nullable|numeric",
                "monthly_payment"   => "nullable|numeric",
                "total_payment"     => "nullable|numeric",
                "total_interest"    => "nullable|numeric",
                "purpose_loan"      => "nullable|string",
                "valid_id"          => "nullable|file|mimes:jpg,jpeg,png,pdf|max:2048",
                "proof_of_income"   => "nullable|file|mimes:jpg,jpeg,png,pdf|max:2048",
            ]);

            // Store files and get their paths
            $validIdPath       = null;
            $proofOfIncomePath = null;

            if ($request->hasFile('valid_id')) {
                $validIdPath = $request->file('valid_id')->store('documents/valid_id', 'public');
            }

            if ($request->hasFile('proof_of_income')) {
                $proofOfIncomePath = $request->file('proof_of_income')->store('documents/proof_of_income', 'public');
            }

            // Generate reference number and date filed
            $referenceNo = 'LN-' . date('YmdHis') . rand(10, 99);
            $dateFiled   = now()->format('M d, Y');

            lending_program_tbl::create([
                "member_id"         => auth()->id(),
                "reference_no"      => $referenceNo,
                "lending_type"      => $request->lending_type,
                "lending_amount"    => $request->lending_amount,
                "lending_type_term" => $request->lending_type_term,
                "monthly_income"    => $request->monthly_income,
                "monthly_payment"   => $request->monthly_payment,
                "total_payment"     => $request->total_payment,
                "total_interest"    => $request->total_interest,
                "purpose_loan"      => $request->purpose_loan,
                "valid_id"          => $validIdPath,
                "proof_of_income"   => $proofOfIncomePath,
                "status"            => "Pending",
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