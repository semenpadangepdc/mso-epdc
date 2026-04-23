<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    protected $fillable = [
        'code',
        'name'
    ];

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function msoTransactions()
    {
        return $this->hasMany(MSOTransaction::class);
    }
}
