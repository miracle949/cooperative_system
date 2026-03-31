<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Users_tbl extends Authenticatable
{
    protected $table = "users_tbls";

    protected $fillable = [
        "membership_category",
        "first_name",
        "middle_name",
        "last_name",
        "profile_picture",  
        "email",
        "password",
        "date_of_birth",
        "place_of_birth",
        "number_son",
        "number_daughter",
        "other_spec",
        "contact_no",
        "civil_status",
        "present_address",
        "permanent_address",
        "sex",
        "citizenship",
        "driver_license_no",
        "tsc_name",
        "skills",
        "height",
        "weight",
        "blood_type",
        "role",
    ];

    public function getAllUser(){
        return $this->all();
    }

    public function savingsAccount()
    {
        return $this->hasOne(savings_account_tbl::class, 'member_id');
    }

    public function lendingPrograms()
    {
        return $this->hasMany(lending_program_tbl::class, 'member_id');
    }
}
