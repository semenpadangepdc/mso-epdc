<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nomenclature extends Model
{
    protected $fillable = [
        'plant_id', 'area_id', 'name', 'default_status'
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function transactions()
    {
        return $this->hasMany(MSOTransaction::class);
    }

    public function components()
    {
        return $this->belongsToMany(
            Component::class,
            'nomenclature_components'
        )->withTimestamps();
    }

    protected static function booted()
    {
        static::created(function ($nomenclature) {

            $components = Component::where(
                'type',
                $nomenclature->type
            )->pluck('id');

            $nomenclature->components()->sync($components);
        });
    }
}
