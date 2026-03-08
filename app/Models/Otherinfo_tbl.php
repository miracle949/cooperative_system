<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otherinfo_tbl extends Model
{
    protected $table = "otherinfo_tbls";

    protected $fillable = [
        "member_id",
        "is_member_other_coop",
        "are_you_willing_liability",
        "are_you_willing_abide_policy",
        "submitted_at",
        "day_of",
        "signature",
        "status",
        "meeting_type",
        "meeting_location",
        "meeting_date"
    ];
}
