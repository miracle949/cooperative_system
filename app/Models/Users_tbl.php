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
        "status",
        "sidebar_permissions",
    ];

    protected $casts = [
        'sidebar_permissions' => 'array',
    ];

    public function otherinfo()
    {
        return $this->hasOne(\App\Models\otherinfo_tbl::class, 'user_id', 'id');
    }

    public function getAllUser()
    {
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

    public function shareCapitalAccount()
    {
        return $this->hasOne(share_capital_account_tbl::class, 'user_id');
    }

    public function isMainAdmin(): bool
    {
        if ($this->role !== 'admin') return false;
        $firstAdmin = self::where('role', 'admin')->orderBy('id')->first();
        return $firstAdmin && $this->id === $firstAdmin->id;
    }
}
