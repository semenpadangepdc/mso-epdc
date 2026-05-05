<?php

namespace App\Services\Dashboard;

use App\Models\MsoTransaction;
use App\Models\Nomenclature;
use Illuminate\Support\Facades\DB;
use App\Models\ProductionCalendar;
use App\Services\Dashboard\Traits\PeriodFilter;

class ReliabilityService
{
    use PeriodFilter;

    /**
     * "Main Filter" pada dashboard = nomenclature bertipe Main Filter DAN ESP
     */
    protected $mainFilterTypes = ['Main Filter', 'ESP'];

    /**
     * maintenance_type_id untuk Corrective / Breakdown
     */
    protected $correctiveId = 2;

    public function getData($filters)
    {
        $filters = is_array($filters) ? $filters : [];
        $filters['year']            = $filters['year']            ?? now()->year;
        $filters['period']          = $filters['period']          ?? 'yearly';
        $filters['month']           = $filters['month']           ?? null;
        $filters['week']            = $filters['week']            ?? null;
        $filters['nomenclature_id'] = $filters['nomenclature_id'] ?? null;
        $filters['mf_type']         = $filters['mf_type']         ?? null;

        return [
            // Per Unit (nomenclature)
            'availability_unit'       => $this->getAvailabilityUnit($filters),
            'downtime_frequency_unit' => $this->getDowntimeFrequencyUnit($filters),

            // Per Nomenclature Main Filter (BHF / ESP per area+plant)
            'availability_main_filter' => $this->getAvailabilityMainFilter($filters),
            'downtime_frequency_area'  => $this->getDowntimeFrequencyArea($filters),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Base query helper — corrective + Closed + Main Filter types
    | Sudah di-filter period (year / year+month / year+week / year+month+week)
    |--------------------------------------------------------------------------
    */
    private function downtimeBaseQuery(array $filters)
    {
        $year   = $filters['year']   ?? now()->year;
        $month  = $filters['month']  ?? null;
        $week   = $filters['week']   ?? null;
        $period = $filters['period'] ?? 'yearly';

        $query = MsoTransaction::query()
            ->join('nomenclatures', 'nomenclatures.id', '=', 'mso_transactions.nomenclature_id')
            ->where('mso_transactions.maintenance_type_id', $this->correctiveId)
            ->where('mso_transactions.status_pekerjaan', 'Closed')
            ->whereNotNull('mso_transactions.start_date')
            ->whereIn('nomenclatures.type', $this->mainFilterTypes)
            ->whereYear('mso_transactions.start_date', $year);

        // Tahunan s.d. pekan ke-X  →  period=yearly  + week diisi
        // Bulanan s.d. pekan ke-X  →  period=monthly + month + week diisi
        // Pekanan                  →  period=weekly  + week diisi

        if (in_array($period, ['monthly', 'weekly']) && $month) {
            $query->whereMonth('mso_transactions.start_date', $month);
        }

        if ($week) {
            if ($period === 'weekly') {
                // Tepat pada pekan tersebut
                $query->whereRaw('WEEK(mso_transactions.start_date, 1) = ?', [$week]);
            } else {
                // "s.d. pekan ke-X" — semua pekan <= week dalam tahun/bulan itu
                $query->whereRaw('WEEK(mso_transactions.start_date, 1) <= ?', [$week]);
            }
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | Planned hours dari production_calendars (dengan period filter)
    |--------------------------------------------------------------------------
    */
    private function getPlannedHours(array $filters, string $groupBy = 'area_id')
    {
        $year   = $filters['year']   ?? now()->year;
        $month  = $filters['month']  ?? null;
        $week   = $filters['week']   ?? null;
        $period = $filters['period'] ?? 'yearly';

        $query = ProductionCalendar::query()
            ->where('year', $year);

        if (in_array($period, ['monthly', 'weekly']) && $month) {
            $query->where('month', $month);
        }

        // Untuk "s.d. pekan ke-X": filter bulan s.d. bulan yang mengandung week tsb
        // production_calendars biasanya per bulan, jadi cukup filter bulan
        // Jika ada kolom week di production_calendars bisa ditambahkan di sini

        return $query
            ->select($groupBy, DB::raw('SUM(planned_production_hours) as planned_hours'))
            ->groupBy($groupBy)
            ->pluck('planned_hours', $groupBy);
    }

    /*
    |--------------------------------------------------------------------------
    | 1. Availability per Unit (per nomenclature)
    |--------------------------------------------------------------------------
    | availability = (planned_hours - downtime) / planned_hours * 100
    | planned_hours untuk unit diambil dari production_calendars per area,
    | lalu dibagi jumlah unit sejenis di area tersebut agar proporsional.
    |--------------------------------------------------------------------------
    */
    private function getAvailabilityUnit(array $filters)
    {
        // Downtime per nomenclature
        $downtimePerUnit = $this->downtimeBaseQuery($filters)
            ->select(
                'mso_transactions.nomenclature_id',
                'nomenclatures.name as unit_name',
                'nomenclatures.area_id',
                DB::raw('SUM(mso_transactions.total_duration) as total_downtime'),
                DB::raw('COUNT(*) as frequency')
            )
            ->groupBy(
                'mso_transactions.nomenclature_id',
                'nomenclatures.name',
                'nomenclatures.area_id'
            )
            ->get();

        // Planned hours per area
        $plannedPerArea = $this->getPlannedHours($filters, 'area_id');

        // Jumlah unit per area (untuk membagi planned hours secara proporsional)
        $unitCountPerArea = Nomenclature::query()
            ->whereIn('type', $this->mainFilterTypes)
            ->select('area_id', DB::raw('COUNT(*) as unit_count'))
            ->groupBy('area_id')
            ->pluck('unit_count', 'area_id');

        return $downtimePerUnit->map(function ($item) use ($plannedPerArea, $unitCountPerArea) {
            $areaPlanned = $plannedPerArea[$item->area_id] ?? 0;
            $unitCount   = $unitCountPerArea[$item->area_id] ?? 1;

            // Planned per unit = planned area dibagi jumlah unit di area
            $plannedUnit = $unitCount > 0 ? ($areaPlanned / $unitCount) : 0;
            $downtime    = $item->total_downtime ?? 0;

            $availability = $plannedUnit > 0
                ? round((($plannedUnit - $downtime) / $plannedUnit) * 100, 2)
                : 0;

            return [
                'nomenclature_id' => $item->nomenclature_id,
                'unit_name'       => $item->unit_name,
                'area_id'         => $item->area_id,
                'planned_hours'   => round($plannedUnit, 2),
                'downtime_hours'  => round($downtime, 2),
                'frequency'       => $item->frequency,
                'availability'    => $availability,
            ];
        })->sortBy('unit_name')->values();
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Downtime Frequency Rate per Unit (per nomenclature)
    |--------------------------------------------------------------------------
    | frequency_rate = jumlah kejadian breakdown per unit dalam periode
    |--------------------------------------------------------------------------
    */
    private function getDowntimeFrequencyUnit(array $filters)
    {
        return $this->downtimeBaseQuery($filters)
            ->select(
                'mso_transactions.nomenclature_id',
                'nomenclatures.name as unit_name',
                'nomenclatures.area_id',
                DB::raw('COUNT(*) as frequency'),
                DB::raw('SUM(mso_transactions.total_duration) as total_downtime')
            )
            ->groupBy(
                'mso_transactions.nomenclature_id',
                'nomenclatures.name',
                'nomenclatures.area_id'
            )
            ->orderByDesc('frequency')
            ->get()
            ->map(fn($item) => [
                'nomenclature_id' => $item->nomenclature_id,
                'unit_name'       => $item->unit_name,
                'area_id'         => $item->area_id,
                'frequency'       => $item->frequency,
                'total_downtime'  => round($item->total_downtime ?? 0, 2),
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Availability Main Filter — per Nomenclature (BHF / ESP) per Area
    |--------------------------------------------------------------------------
    | Setiap baris = 1 nomenclature (misal "Finish Mill 4") pada 1 area,
    | sehingga bisa langsung diketahui unit mana yang nilainya rendah.
    |
    | Filter opsional $filters['nomenclature_id'] untuk drilldown ke 1 unit.
    |--------------------------------------------------------------------------
    */
    private function getAvailabilityMainFilter(array $filters)
    {
        $nomenclatureId = $filters['nomenclature_id'] ?? null;
        $mfType         = $filters['mf_type']         ?? null;

        // Downtime per nomenclature (sudah di-scope ke mainFilterTypes di downtimeBaseQuery)
        $downtimeQuery = $this->downtimeBaseQuery($filters)
            ->select(
                'mso_transactions.nomenclature_id',
                'nomenclatures.name as nomenclature_name',
                'nomenclatures.area_id',
                'nomenclatures.type as nomenclature_type',
                DB::raw('SUM(mso_transactions.total_duration) as total_downtime'),
                DB::raw('COUNT(*) as frequency')
            )
            ->groupBy(
                'mso_transactions.nomenclature_id',
                'nomenclatures.name',
                'nomenclatures.area_id',
                'nomenclatures.type'
            );

        if ($nomenclatureId) {
            $downtimeQuery->where('mso_transactions.nomenclature_id', $nomenclatureId);
        }

        $downtimePerUnit = $downtimeQuery->get()->keyBy('nomenclature_id');

        // Planned hours per area — lalu bagi per jumlah unit sejenis di area
        $plannedPerArea = $this->getPlannedHours($filters, 'area_id');

        $unitCountPerArea = Nomenclature::query()
            ->whereIn('type', $this->mainFilterTypes)
            ->select('area_id', DB::raw('COUNT(*) as unit_count'))
            ->groupBy('area_id')
            ->pluck('unit_count', 'area_id');

        // Ambil semua nomenclature mainFilterTypes sebagai basis baris
        $nomenclatureQuery = Nomenclature::query()
            ->whereIn('type', $this->mainFilterTypes)
            ->with(['area', 'plant'])
            ->select('id', 'name', 'area_id', 'plant_id', 'type');

        if ($nomenclatureId) {
            $nomenclatureQuery->where('id', $nomenclatureId);
        }

        if ($mfType) {
            $nomenclatureQuery->where('type', $mfType);
        }

        $allNomenclatures = $nomenclatureQuery->get();

        return $allNomenclatures->map(function ($nom) use (
            $downtimePerUnit, $plannedPerArea, $unitCountPerArea
        ) {
            $areaPlanned = $plannedPerArea[$nom->area_id] ?? 0;
            $unitCount   = $unitCountPerArea[$nom->area_id] ?? 1;
            $plannedUnit = $unitCount > 0 ? ($areaPlanned / $unitCount) : 0;

            $dt           = $downtimePerUnit[$nom->id] ?? null;
            $downtime     = $dt->total_downtime ?? 0;
            $frequency    = $dt->frequency      ?? 0;

            $availability = $plannedUnit > 0
                ? round((($plannedUnit - $downtime) / $plannedUnit) * 100, 2)
                : null; // null = belum ada data planned

            // Label informatif: "Finish Mill 4" atau "Finish Mill — Indarung 4"
            $plantName = $nom->plant->name ?? null;
            $label     = $nom->name . ($plantName ? ' — ' . $plantName : '');

            return [
                'nomenclature_id'   => $nom->id,
                'nomenclature_name' => $nom->name,
                'nomenclature_type' => $nom->type,
                'area_id'           => $nom->area_id,
                'area_name'         => $nom->area->name ?? null,
                'plant_id'          => $nom->plant_id,
                'plant_name'        => $plantName,
                'label'             => $label,
                'planned_hours'     => round($plannedUnit, 2),
                'downtime_hours'    => round($downtime, 2),
                'frequency'         => $frequency,
                'availability'      => $availability,
            ];
        })
        ->sortBy('label')
        ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Downtime Frequency Rate per Area
    |--------------------------------------------------------------------------
    */
    private function getDowntimeFrequencyArea(array $filters)
    {
        return $this->downtimeBaseQuery($filters)
            ->select(
                'nomenclatures.area_id',
                DB::raw('COUNT(*) as frequency'),
                DB::raw('SUM(mso_transactions.total_duration) as total_downtime')
            )
            ->groupBy('nomenclatures.area_id')
            ->orderByDesc('frequency')
            ->get()
            ->map(fn($item) => [
                'area_id'        => $item->area_id,
                'frequency'      => $item->frequency,
                'total_downtime' => round($item->total_downtime ?? 0, 2),
            ]);
    }
}