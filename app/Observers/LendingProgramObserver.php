<?php

namespace App\Observers;

use App\Models\lending_program_tbl;
use App\Models\lending_status_tbl;

class LendingProgramObserver
{
    public function updated(lending_program_tbl $loan)
    {
        if ($loan->isDirty('status') && $loan->status === 'Approved') {

            $exists = lending_status_tbl::where('lending_id', $loan->id)->exists();
            if ($exists)
                return;

            lending_status_tbl::create([
                'lending_id' => $loan->id,
                'member_id' => $loan->member_id,
                'remaining_balance' => $loan->total_payment, // ← must be total_payment not lending_amount
                'total_paid' => 0,
                'payments_made' => 0,
                'total_payments' => (int) filter_var($loan->lending_type_term, FILTER_SANITIZE_NUMBER_INT),
                'interest_rate' => ($loan->lending_amount > 0 && $loan->total_interest > 0)
                    ? round(($loan->total_interest / $loan->lending_amount) * 100, 2)
                    : 0,
                'next_due_date' => now()->addMonth()->format('Y-m-d'),
                'status' => 'Active',
            ]);
        }
    }
}