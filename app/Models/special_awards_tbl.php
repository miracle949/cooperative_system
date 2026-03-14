<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class special_awards_tbl extends Model
{
    protected $table = "special_awards_tbls";

    protected $fillable = [
        "member_id",
        "title_awards",
        "awarded_by",
        "membership_other_association"
    ];
}
