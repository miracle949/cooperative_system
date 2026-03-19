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
        "balance_after",
        "note",
        "transaction_date",
    ];
}
