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
        try {
            if (!env('PAYMONGO_SECRET_KEY')) {
                return redirect()->back()->with('error', 'Payment gateway is not configured yet.');
            }

            $loan = lending_program_tbl::findOrFail($request->lending_id);
            $paymentType = $request->payment_type ?? 'monthly'; // ADD THIS

            // Determine the correct amount to charge
            if ($paymentType === 'full') {
                $status = lending_status_tbl::where('lending_id', $loan->id)->first();
                $amount = $status ? $status->remaining_balance : $loan->total_payment;
            } else {
                $amount = $loan->monthly_payment;
            }

            $response = Http::withBasicAuth(env('PAYMONGO_SECRET_KEY'), '')
                ->post('https://api.paymongo.com/v1/sources', [
                    'data' => [
                        'attributes' => [
                            'amount' => (int) ($amount * 100), // use $amount instead of monthly_payment
                            'currency' => 'PHP',
                            'type' => 'gcash',
                            'redirect' => [
                                'success' => route('repayment.gcash.success', [
                                    'lending_id' => $loan->id,
                                    'payment_type' => $paymentType, // PASS IT TO SUCCESS URL
                                ]),
                                'failed' => route('repayment.gcash.failed', ['lending_id' => $loan->id]),
                            ],
                        ],
                    ],
                ]);

            $data = $response->json();

            if (isset($data['errors'])) {
                return redirect()->back()->with('error', $data['errors'][0]['detail']);
            }

            if (isset($data['data']['attributes']['redirect']['checkout_url'])) {
                return redirect($data['data']['attributes']['redirect']['checkout_url']);
            }

            return redirect()->back()->with('error', 'GCash payment failed.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function gcashSuccess(Request $request)
    {
        $lendingId = $request->get('lending_id');
        $paymentType = $request->get('payment_type', 'monthly'); // ADD THIS
        $loan = lending_program_tbl::findOrFail($lendingId);
        $status = lending_status_tbl::where('lending_id', $lendingId)->first();

        if ($paymentType === 'full' && $status) {
            // ── FULL REPAYMENT ────────────────────────────────────────────
            $remainingBalance = $status->remaining_balance;
            $remainingPayments = $status->total_payments - $status->payments_made;

            // Create one repayment record per remaining payment
            for ($i = 1; $i <= $remainingPayments; $i++) {
                $paymentsMade = lending_repayments_tbl::where('lending_id', $lendingId)->count();
                lending_repayments_tbl::create([
                    'lending_id' => $lendingId,
                    'user_id' => auth()->id(),
                    'payment_number' => $paymentsMade + 1,
                    'amount_paid' => $loan->monthly_payment,
                    'payment_date' => now()->format('Y-m-d'),
                    'payment_method' => 'GCash',
                    'reference_no' => 'GCASH-FULL-' . now()->format('YmdHis') . '-' . $i,
                    'notes' => 'Full balance repayment via GCash',
                    'recorded_by' => null,
                ]);
            }

            // Mark loan as fully paid
            $status->total_paid += $remainingBalance;
            $status->remaining_balance = 0;
            $status->payments_made = $status->total_payments;
            $status->status = 'Completed';
            $status->save();

            lending_program_tbl::where('id', $lendingId)->update(['status' => 'Completed']);

        } else {
            // ── SINGLE MONTHLY PAYMENT (existing logic) ───────────────────
            $paymentsMade = lending_repayments_tbl::where('lending_id', $lendingId)->count();

            lending_repayments_tbl::create([
                'lending_id' => $lendingId,
                'user_id' => auth()->id(),
                'payment_number' => $paymentsMade + 1,
                'amount_paid' => $loan->monthly_payment,
                'payment_date' => now()->format('Y-m-d'),
                'payment_method' => 'GCash',
                'reference_no' => 'GCASH-' . now()->format('YmdHis'),
                'notes' => 'Paid via GCash',
                'recorded_by' => null,
            ]);

            if ($status) {
                $status->total_paid += $loan->monthly_payment;
                $status->remaining_balance = max(0, $status->remaining_balance - $loan->monthly_payment);
                $status->payments_made += 1;
                $status->due_date = now()->addMonth()->format('Y-m-d');

                if ($status->remaining_balance <= 0 || $status->payments_made >= $status->total_payments) {
                    $status->status = 'Completed';
                    $status->payments_made = $status->total_payments;
                    lending_program_tbl::where('id', $lendingId)->update(['status' => 'Completed']);
                }

                $status->save();
            }
        }

        return redirect()->route('LoanStatus', ['loan_id' => $lendingId])
            ->with('success', 'GCash payment successful!');
    }

    public function gcashFailed(Request $request)
    {
        $lendingId = $request->get('lending_id');

        return redirect()->route('LoanStatus', ['lending_id' => $lendingId])
            ->with('error', 'GCash payment failed. Please try again.');
    }
}