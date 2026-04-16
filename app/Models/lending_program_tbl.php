<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lending_program_tbl extends Model
{
    protected $table = "lending_program_tbls";

    protected $fillable = [
        'user_id',
        'reference_no',
        'lending_type',
        'lending_amount',
        'lending_type_term',
        'monthly_income',
        'monthly_payment',
        'total_payment',
        'total_interest',
        'purpose_loan',
        'status',
        'decline_reason',

        // Shared
        'valid_id',
        'proof_of_income',

        // Emergency
        'proof_of_emergency',

        // Business
        'business_permit',
        'financial_statement',

        // Education
        'school_id',
        'cor',
        'cog',
    ];

    public function user()
    {
        return $this->belongsTo(Users_tbl::class, 'user_id');
    }
}
