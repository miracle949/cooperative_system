<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lending_repayments_tbl extends Model
{
    public $incrementing = true;
    protected $table = "lending_repayments_tbls";

    protected $fillable = [
        "lending_id",
        "user_id",
        "payment_number",
        "amount_due",
        "amount_paid",
        "due_date",
        "payment_date",
        "late_fee",
        "penalty_applied_at",
        "payment_method",
        "reference_no",
        "notes",
        "recorded_by",
    ];
}
