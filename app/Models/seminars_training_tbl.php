<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class seminars_training_tbl extends Model
{
    protected $table = "seminars_training_tbls";

    protected $fillable = [
        "member_id",
        "title_seminar",
        "attendance_from",
        "attendance_to",
        "sponsored_by"
    ];
}
