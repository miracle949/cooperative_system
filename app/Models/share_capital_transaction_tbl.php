<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class share_capital_transaction_tbl extends Model
{
    public $incrementing = true;
    protected $table = "share_capital_transaction_tbls";

    protected $fillable = [
        "share_capital_account_id",
        "type",
        "shares",
        "amount_per_share",
        "total_amount",
        "payment_method",
        "reference_no",
        "note",
        "status",
        "transaction_date",
    ];

    public function shareCapitalAccount()
    {
        return $this->belongsTo(share_capital_account_tbl::class, 'share_capital_account_id');
    }
}
