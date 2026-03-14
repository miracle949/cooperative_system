<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tc_membership_history_tbl extends Model
{
    protected $table = "tc_membership_history_tbls";

    protected $fillable = [
        "member_id",
        "members_inclusive_dates_from",
        "members_inclusive_dates_to",
        "membership_category",
        "tc_held_inclusive_dates_from",
        "tc_held_inclusive_dates_to",
        "position_held",
        "monthly_salary_allowance"
    ];
}
