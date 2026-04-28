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
            
            $table->string('pengadaan', 50)->nullable();
            $table->check("pengadaan IN ('Jasa','Barang-Jasa','Via Peng.Barang','Via Capex')");
            
            $table->string('model', 50)->nullable();
            $table->check("model IN ('Tender','TL')");
            
            $table->string('nomor_reservasi')->nullable();
            $table->string('nomor_pr')->nullable();
            $table->string('nomor_po')->nullable();
            $table->decimal('estimasi_harga', 15, 2)->nullable();
            $table->string('nama_vendor')->nullable();
            
            $table->string('status', 50)->default('Open');
            $table->check("status IN ('Open','Closed')");
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_requests');
    }
};