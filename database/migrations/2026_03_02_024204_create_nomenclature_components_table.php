<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nomenclature_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nomenclature_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('component_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('material_number', 50)->nullable();
            $table->string('description', 255)->nullable();

            $table->timestamps();

            $table->unique(['nomenclature_id', 'component_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nomenclature_components');
    }
};