<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_requests', function (Blueprint $table) {
            $table->id();
            $table->string('trans_id');
            $table->string('nomenclature');
            $table->string('component');
            $table->text('abnormality');
            $table->text('action');
            $table->string('material_master')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('no_notifikasi')->nullable();
            $table->integer('qty')->nullable();
            $table->string('uom')->nullable();
            $table->enum('pengadaan', [
                'Jasa',
                'Barang-Jasa',
                'Via Peng.Barang',
                'Via Capex'
            ])->nullable();
            $table->enum('model', [
                'Tender',
                'TL'
            ])->nullable();
            $table->string('nomor_reservasi')->nullable();
            $table->string('nomor_pr')->nullable();
            $table->string('nomor_po')->nullable();
            $table->decimal('estimasi_harga', 15, 2)->nullable();
            $table->string('nama_vendor')->nullable();
            $table->enum('status', [
                'Open',
                'Closed'
            ])->default('Open');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_requests');
    }
};