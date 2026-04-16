<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan_settings_tbl extends Model
{
    protected $table = "loan_settings_tbls";

    protected $fillable = [
        "loan_type",
        "interest_rate",
        "max_amount",
        "late_fee_percentage",
        "grace_period_months",
    ];

    public static function getRate($loanType)
    {
        $setting = self::where('loan_type', $loanType)->first();
        return $setting ? $setting->interest_rate : 2.00;
    }

    public static function getAllRates()
    {
        return self::pluck('interest_rate', 'loan_type')->toArray();
    }
}