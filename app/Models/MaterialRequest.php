<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequest extends Model
{
    protected $fillable = [
        'trans_id',
        'nomenclature',
        'component',
        'abnormality',
        'action',
        'material_master',
        'tanggal',
        'no_notifikasi',
        'qty',
        'uom',
        'pengadaan',
        'model',
        'nomor_reservasi',
        'nomor_pr',
        'nomor_po',
        'estimasi_harga',
        'nama_vendor',
        'status'
    ];
}