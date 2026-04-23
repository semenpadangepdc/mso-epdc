<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LookupValue extends Model
{
    protected $fillable = [
        'type',
        'key',
        'value'
    ];

    public $timestamps = false;

    public static function getByType($type)
    {
        return self::where('type', $type)->get();
    }
}
