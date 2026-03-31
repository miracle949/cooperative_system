<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class share_capital_account_tbl extends Model
{
    protected $table = "share_capital_account_tbls";

    protected $fillable = [
        "user_id",
        "total_shares",
        "total_amount",
        "status",
    ];
}
