<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class savings_transaction_tbl extends Model
{
    protected $table = "savings_transaction_tbls";

    protected $fillable = [
        "savings_account_id",
        "type",
        "amount",
        "payment_method",
        "balance_after",
        "note",
        "reference_no",
        "transaction_date",
        "status",
    ];

    public function savingsAccount()
    {
        return $this->belongsTo(savings_account_tbl::class, 'savings_account_id');
    }
}
