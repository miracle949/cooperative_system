<?php

namespace App\Http\Controllers;

use App\Models\lending_program_tbl;
use App\Models\lending_status_tbl;
use App\Models\lending_repayments_tbl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function payViaGcash(Request $request)
    {
        if (!env('PAYMONGO_SECRET_KEY')) {
            return redirect()->back()->with('error', 'Payment gateway is not configured yet.');
        }

        $loan = lending_program_tbl::findOrFail($request->lending_id);

        $response = Http::withBasicAuth(env('PAYMONGO_SECRET_KEY'), '')
            ->post('https://api.paymongo.com/v1/sources', [
                'data' => [
                    'attributes' => [
                        'amount' => (int) ($loan->monthly_payment * 100),
                        'currency' => 'PHP',
                        'type' => 'gcash',
                        'redirect' => [
                            'success' => route('repayment.gcash.success', ['lending_id' => $loan->id]),
                            'failed' => route('repayment.gcash.failed', ['lending_id' => $loan->id]),
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

    public function gcashSuccess(Request $request)
    {
        $lendingId = $request->get('lending_id');
        $loan = lending_program_tbl::findOrFail($lendingId);

        // Get current payment number
        $paymentsMade = lending_repayments_tbl::where('lending_id', $lendingId)->count();

        // Save repayment record
        lending_repayments_tbl::create([
            'lending_id' => $lendingId,
            'member_id' => auth()->id(),
            'payment_number' => $paymentsMade + 1,
            'amount_paid' => $loan->monthly_payment,
            'payment_date' => now()->format('Y-m-d'),
            'payment_method' => 'GCash',
            'reference_no' => 'GCASH-' . now()->format('YmdHis'),
            'notes' => 'Paid via GCash',
            'recorded_by' => null,
        ]);

        // Update lending status
        $status = lending_status_tbl::where('lending_id', $lendingId)->first();
        if ($status) {
            $status->total_paid += $loan->monthly_payment;
            $status->remaining_balance = max(0, $status->remaining_balance - $loan->monthly_payment);
            $status->payments_made += 1;
            $status->next_due_date = now()->addMonth()->format('Y-m-d');
            if ($status->remaining_balance <= 0) {
                $status->status = 'Completed';
            }
            $status->save();
        }

        return redirect()->route('LoanStatus', ['loan_id' => $lendingId])
            ->with('success', 'GCash payment successful!');
    }

    public function gcashFailed(Request $request)
    {
        $lendingId = $request->get('lending_id');

        return redirect()->route('LoanStatus', ['loan_id' => $lendingId])
            ->with('error', 'GCash payment failed. Please try again.');
    }
}