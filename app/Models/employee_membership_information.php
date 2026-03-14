<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class employee_membership_information extends Model
{
    protected $table = "employee_membership_informations";

    protected $fillable = [
        "member_id",
        "date_of_membership",
        "date_of_cetos",
        "membership_category",
        "tc_member_id_no",
        "no_units_owned",
        "type_mode_unit",
        "paid_up_capital",
        "paid_up_price"
    ];
}
