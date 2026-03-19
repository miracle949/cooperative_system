<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class savings_account_tbl extends Model
{
    protected $table = "savings_account_tbls";

    protected $fillable = [
        "member_id",
        "balance",
        "status",
        "balance_after",
        "opened_at"
    ];
}
