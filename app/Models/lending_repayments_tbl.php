<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lending_repayments_tbl extends Model
{
    protected $table = "lending_repayments_tbls";

    protected $fillable = [
        "lending_id",
        "user_id",
        "payment_number",
        "amount_paid",
        "payment_date",
        "payment_method",
        "reference_no",
        "notes",
        "recorded_by",
    ];
}
