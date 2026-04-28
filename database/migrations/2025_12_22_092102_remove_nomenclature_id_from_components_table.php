<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('components', function (Blueprint $table) {
            if (Schema::hasColumn('components', 'nomenclature_id')) {
                $table->dropForeign(['nomenclature_id']);
                $table->dropColumn('nomenclature_id');
            }
        });
    }

    public function down()
    {
        Schema::table('components', function (Blueprint $table) {
            $table->foreignId('nomenclature_id')
                  ->constrained()
                  ->cascadeOnDelete();
        });
    }
};