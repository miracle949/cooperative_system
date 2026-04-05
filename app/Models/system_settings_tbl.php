<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class system_settings_tbl extends Model
{
    protected $table = "system_settings_tbls";

    protected $fillable = [
        "key",
        "value",
    ];

    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue($key, $value)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}