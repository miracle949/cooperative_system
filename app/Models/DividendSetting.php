<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DividendSetting extends Model
{
    protected $table = 'dividend_settings_tbls';

    protected $fillable = [
        'year',
        'dividend_fund_percentage',
        'updated_by',
    ];

    public function updater()
    {
        return $this->belongsTo(Users_tbl::class, 'updated_by');
    }

    public static function getForYear($year)
    {
        return static::firstOrCreate(
            ['year' => $year],
            ['dividend_fund_percentage' => 60.00]
        );
    }
}
