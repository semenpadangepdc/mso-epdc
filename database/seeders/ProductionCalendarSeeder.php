<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plant;
use App\Models\ProductionCalendar;
use Carbon\Carbon;

class ProductionCalendarSeeder extends Seeder
{
    public function run(): void
    {
        $year = 2026;

        $plants = Plant::with('areas')->get();

        foreach ($plants as $plant) {

            foreach ($plant->areas as $area) {

                for ($month = 1; $month <= 12; $month++) {

                    $date = Carbon::create($year, $month, 1);

                    $totalDays = $date->daysInMonth;

                    // Asumsi 2 hari shutdown per bulan
                    $plannedShutdownDays = 2;

                    // Jangan sampai shutdown > total hari
                    if ($plannedShutdownDays > $totalDays) {
                        $plannedShutdownDays = 0;
                    }

                    $plannedRunningDays = $totalDays - $plannedShutdownDays;

                    $plannedProductionHours = $plannedRunningDays * 24;
                    $plannedDowntimeHours   = $plannedShutdownDays * 24;

                    ProductionCalendar::updateOrCreate(
                        [
                            'plant_id' => $plant->id,
                            'area_id'  => $area->id,
                            'year'     => $year,
                            'month'    => $month,
                        ],
                        [
                            'date'                     => $date->startOfMonth(),
                            'total_days'               => $totalDays,
                            'planned_running_days'     => $plannedRunningDays,
                            'planned_shutdown_days'    => $plannedShutdownDays,
                            'planned_production_hours' => $plannedProductionHours,
                            'planned_downtime_hours'   => $plannedDowntimeHours,
                        ]
                    );
                }
            }
        }
    }
}