<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spouse_tbl extends Model
{
    protected $table = "spouse_tbls";
    
    protected $fillable = [
        "member_id",
        "spouse_name",
        "spouse_date_birth",
        "spouse_place_birth"
    ];
}
