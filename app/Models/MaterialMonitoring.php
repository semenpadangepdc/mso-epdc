<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialMonitoring extends Model
{
    use HasFactory;

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
        'tanggal_reservasi',  // ✅ BARU

        'nomor_pr',
        'tanggal_pr',         // ✅ BARU

        'nomor_po',
        'tanggal_po',         // ✅ BARU

        'estimated_delivery', // ✅ BARU

        'estimasi_harga',
        'nama_vendor',
        'status'
    ];

    /**
     * Cast kolom tanggal agar otomatis menjadi Carbon instance.
     */
    protected $casts = [
        'tanggal'            => 'date',
        'tanggal_reservasi'  => 'date',
        'tanggal_pr'         => 'date',
        'tanggal_po'         => 'date',
        'estimated_delivery' => 'date',
    ];
}