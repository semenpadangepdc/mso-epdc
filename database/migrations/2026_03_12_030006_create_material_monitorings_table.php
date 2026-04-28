<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_monitorings', function (Blueprint $table) {
            $table->id();
            $table->string('trans_id');
            $table->string('nomenclature')->nullable();
            $table->string('component')->nullable();
            $table->text('abnormality')->nullable();
            $table->text('action')->nullable();
            $table->string('material_master')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('no_notifikasi')->nullable();
            $table->integer('qty')->nullable();
            $table->string('uom')->nullable();
            $table->string('pengadaan')->nullable();
            $table->string('model')->nullable();
            $table->string('nomor_reservasi')->nullable();
            $table->date('tanggal_reservasi')->nullable();
            $table->string('nomor_pr')->nullable();
            $table->date('tanggal_pr')->nullable();
            $table->string('nomor_po')->nullable();
            $table->date('tanggal_po')->nullable();
            $table->date('estimated_delivery')->nullable();
            $table->double('estimasi_harga')->nullable();
            $table->string('nama_vendor')->nullable();
            
            $table->string('status', 50)->default('Open');
            $table->check("status IN ('Open','Closed')");
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_monitorings');
    }
};