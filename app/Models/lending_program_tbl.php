<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lending_program_tbl extends Model
{
    protected $table = "lending_program_tbls";

    protected $fillable = [
        "member_id",
        "reference_no",
        "lending_type",
        "lending_amount",
        "lending_type_term",
        "monthly_income",
        "monthly_payment",
        "total_payment",
        "total_interest",
        "valid_id",
        "proof_of_income",
        "purpose_loan",
        "status"
    ];
}
