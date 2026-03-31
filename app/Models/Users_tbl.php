<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Users_tbl extends Authenticatable
{
    protected $table = "users_tbls";

    protected $fillable = [
        "fullname",
        "username",
        "email",
        "password",
        "role",
    ];

    public function getAllUser(){
        return $this->all();
    }
}
