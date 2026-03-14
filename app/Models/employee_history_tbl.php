<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class employee_history_tbl extends Model
{
    protected $table = "employee_history_tbls";

    protected $fillable = [
        "member_id",
        "name_office",
        "position_title",
        "monthly_salary",
        "employee_inclusive_from",
        "employee_inclusive_to"
    ];  
}
