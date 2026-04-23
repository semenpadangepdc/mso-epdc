<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialMaster extends Model
{
    protected $table = 'material_master';

    protected $fillable = [
        'material_code',
        'material_description',
        'long_text',
        'base_uom',
        'mrp_type',
        'price',
        'material_group',
        'gl_account',
        'safety_stock',
        'critical_part',
    ];

    protected $casts = [
        'critical_part' => 'boolean',
        'price' => 'decimal:2',
    ];
}
