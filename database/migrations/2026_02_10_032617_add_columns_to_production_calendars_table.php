<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('production_calendars', function (Blueprint $table) {

            if (!Schema::hasColumn('production_calendars', 'total_days')) {
                $table->integer('total_days')->after('month');
            }

            if (!Schema::hasColumn('production_calendars', 'planned_running_days')) {
                $table->integer('planned_running_days')->after('total_days');
            }

            if (!Schema::hasColumn('production_calendars', 'planned_shutdown_days')) {
                $table->integer('planned_shutdown_days')->after('planned_running_days');
            }

            if (!Schema::hasColumn('production_calendars', 'planned_production_hours')) {
                $table->integer('planned_production_hours')->after('planned_shutdown_days');
            }

            if (!Schema::hasColumn('production_calendars', 'planned_downtime_hours')) {
                $table->integer('planned_downtime_hours')->after('planned_production_hours');
            }
        });
    }

    public function down(): void
    {
        Schema::table('production_calendars', function (Blueprint $table) {

            if (Schema::hasColumn('production_calendars', 'planned_downtime_hours')) {
                $table->dropColumn('planned_downtime_hours');
            }

            if (Schema::hasColumn('production_calendars', 'planned_production_hours')) {
                $table->dropColumn('planned_production_hours');
            }

            if (Schema::hasColumn('production_calendars', 'planned_shutdown_days')) {
                $table->dropColumn('planned_shutdown_days');
            }

            if (Schema::hasColumn('production_calendars', 'planned_running_days')) {
                $table->dropColumn('planned_running_days');
            }

            if (Schema::hasColumn('production_calendars', 'total_days')) {
                $table->dropColumn('total_days');
            }
        });
    }
};