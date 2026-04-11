<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Users_tbl extends Authenticatable
{
    protected $table = "users_tbls";

    protected $fillable = [
        "first_name",
        "middle_name",
        "last_name",
        "username",
        "email",
        "password",
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
