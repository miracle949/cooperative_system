<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class savings_account_tbl extends Model
{
    protected $table = "savings_account_tbls";

    protected $fillable = [
        "user_id",
        "balance",
        "status",
        "balance_after",
        "opened_at"
    ];

    public function user()
    {
        return $this->belongsTo(Users_tbl::class, 'member_id');
    }

    public function transactions()
    {
        return $this->hasMany(savings_transaction_tbl::class, 'savings_account_id');
    }
}
