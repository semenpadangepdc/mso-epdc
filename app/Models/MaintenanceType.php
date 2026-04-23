<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceType extends Model
{
    protected $fillable = [
        'name'
    ];

    public function components()
    {
        return $this->hasMany(Component::class);
    }

    public function msoTransactions()
    {
        return $this->hasMany(MSOTransaction::class);
    }
}
