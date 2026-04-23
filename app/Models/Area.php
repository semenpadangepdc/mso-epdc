<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'plant_id',
        'name'
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function nomenclatures()
    {
        return $this->hasMany(Nomenclature::class, 'area_code', 'name');
    }


    public function msoTransactions()
    {
        return $this->hasMany(MSOTransaction::class);
    }
}
