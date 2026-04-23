<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_calendars', function (Blueprint $table) {
            $table->id();

            $table->foreignId('plant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('area_id')->constrained()->cascadeOnDelete();

            // ⬇️ INI WAJIB ADA DARI AWAL
            $table->integer('year');
            $table->integer('month');

            // kolom lama (kalau masih mau disimpan)
            $table->date('date')->nullable();

            $table->timestamps();

            $table->unique(['plant_id', 'area_id', 'year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_calendars');
    }
};