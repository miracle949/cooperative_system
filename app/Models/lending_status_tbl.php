<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lending_status_tbl extends Model
{
    protected $table = "lending_status_tbls";

    protected $fillable = [
        "lending_id",
        "member_id",
        "remaining_balance",
        "total_paid",
        "payments_made",
        "total_payments",
        "interest_rate",
        "next_due_date",
        "status",
    ];
}
