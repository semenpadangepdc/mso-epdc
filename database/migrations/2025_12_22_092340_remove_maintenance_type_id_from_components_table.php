<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::table('components', function (Blueprint $table) {
            if (Schema::hasColumn('components', 'maintenance_type_id')) {
                $table->dropForeign(['maintenance_type_id']);
                $table->dropColumn('maintenance_type_id');
            }
        });
    }

    public function down()
    {
        Schema::table('components', function (Blueprint $table) {
            $table->foreignId('maintenance_type_id')
                  ->constrained()
                  ->cascadeOnDelete();
        });
    }
};
