<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mso_transactions', function (Blueprint $table) {
            $table->index('start_date');
            $table->index('maintenance_type_id');
            $table->index('status_pekerjaan');
            $table->index('nomenclature_id');
        });

        Schema::table('nomenclatures', function (Blueprint $table) {
            $table->index('type');
            $table->index('area_id');
            $table->index('plant_id');
        });

        Schema::table('production_calendars', function (Blueprint $table) {
            $table->index(['year','month','area_id']);
        });
    }

    public function down()
    {
        Schema::table('mso_transactions', function (Blueprint $table) {
            $table->dropIndex(['start_date']);
            $table->dropIndex(['maintenance_type_id']);
            $table->dropIndex(['status_pekerjaan']);
            $table->dropIndex(['nomenclature_id']);
        });

        Schema::table('nomenclatures', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['area_id']);
            $table->dropIndex(['plant_id']);
        });

        Schema::table('production_calendars', function (Blueprint $table) {
            $table->dropIndex(['year','month','area_id']);
        });
    }
};