<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = [
        'name',
        'type',
    ];

    public function nomenclatures()
    {
        return $this->belongsToMany(
            Nomenclature::class,
            'nomenclature_components'
        )->withTimestamps();
    }
}
