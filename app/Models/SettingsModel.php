<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingsModel extends Model
{
    //
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get($key)
    {
        return self::where('key', $key)->value('value');
    }

    public static function set($key, $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
