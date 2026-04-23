<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Dashboard\DashboardService;
use App\Services\Dashboard\MaintenancePerformanceService;
use App\Models\Area;

class DashboardController extends Controller
{
    protected $dashboardService;
    protected $maintenanceService;

    public function __construct(
        DashboardService $dashboardService,
        MaintenancePerformanceService $maintenanceService
    ) {
        $this->dashboardService   = $dashboardService;
        $this->maintenanceService = $maintenanceService;
    }

    public function index(Request $request)
    {
        $period = $request->period ?? 'yearly';

        $filters = [
            'period'   => $period,
            'year'     => $request->year ?? now()->year,
            'month'    => $period === 'monthly' ? ($request->month ?? now()->month) : null,
            'week'     => $period === 'weekly'  ? ($request->week  ?? now()->week)  : null,
            'plant_id' => $request->plant_id,
            'area_id'  => $request->area_id,
        ];

        // =========================
        // MAIN DASHBOARD (Reliability)
        // ReliabilityService->getData() mengembalikan:
        //   availability_unit, downtime_frequency_unit,
        //   availability_area, downtime_frequency_area
        // =========================
        $mainDashboard   = $this->dashboardService->getDashboardData($filters);
        $reliabilityData = $mainDashboard['availability'] ?? [];

        // Ambil nama area sekali untuk di-map ke semua data
        $areaNames = Area::pluck('name', 'id');

        // Siapkan $availability untuk blade (per area, dengan key 'area' = nama area)
        $availabilityArea = collect($reliabilityData['availability_area'] ?? [])
            ->map(fn($item) => array_merge($item, [
                'area' => $areaNames[$item['area_id']] ?? 'Area ' . $item['area_id'],
            ]));

        // Siapkan $availability_unit untuk blade (per unit, dengan area name)
        $availabilityUnit = collect($reliabilityData['availability_unit'] ?? [])
            ->map(fn($item) => array_merge($item, [
                'area' => $areaNames[$item['area_id']] ?? 'Area ' . $item['area_id'],
            ]));

        // Frequency per area & unit
        $downtimeFreqArea = collect($reliabilityData['downtime_frequency_area'] ?? [])
            ->map(fn($item) => array_merge($item, [
                'area' => $areaNames[$item['area_id']] ?? 'Area ' . $item['area_id'],
            ]));

        $downtimeFreqUnit = collect($reliabilityData['downtime_frequency_unit'] ?? []);

        // =========================
        // MAINTENANCE PERFORMANCE
        // =========================
        $maintenanceSummary  = $this->maintenanceService->getMaintenanceSummary($filters);
        $pendingAbnormality  = $this->maintenanceService->getPendingAbnormalities($filters);

        $msoListForSummary   = $this->maintenanceService->getMsoListForSummary($filters);
        $msoListForPending   = $this->maintenanceService->getMsoListForPending($filters);

        $top5FrequencyMonthly = $this->maintenanceService->getTopFiveFrequencyMonthly($filters);
        $top5FrequencyYearly  = $this->maintenanceService->getTopFiveFrequencyYearly($filters);

        $top5DurationMonthly  = $this->maintenanceService->getTopFiveDurationMonthly($filters);
        $top5DurationYearly   = $this->maintenanceService->getTopFiveDurationYearly($filters);

        return view('dashboard.index', [
            'filters' => $filters,

            // Availability — per area (dipakai blade saat ini)
            'availability'          => $availabilityArea,
            // Availability — per unit (untuk section baru)
            'availability_unit'     => $availabilityUnit,
            // Downtime frequency
            'downtime_freq_area'    => $downtimeFreqArea,
            'downtime_freq_unit'    => $downtimeFreqUnit,

            'breakdown'             => $mainDashboard['breakdown'] ?? [],

            'maintenance_summary'   => $maintenanceSummary ?? [],
            'pending_abnormality'   => $pendingAbnormality ?? [],

            'mso_list_summary'      => $msoListForSummary ?? collect(),
            'mso_list_pending'      => $msoListForPending ?? collect(),

            'top5_freq_month'       => $top5FrequencyMonthly ?? [],
            'top5_freq_year'        => $top5FrequencyYearly ?? [],

            'top5_dur_month'        => $top5DurationMonthly ?? [],
            'top5_dur_year'         => $top5DurationYearly ?? [],
        ]);
    }
}