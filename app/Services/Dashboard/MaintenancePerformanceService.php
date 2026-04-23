<?php

namespace App\Services\Dashboard;

use App\Models\MsoTransaction;
use Illuminate\Support\Facades\DB;

class MaintenancePerformanceService
{
    /**
     * Default unit types fallback
     */
    protected function getUnitTypes()
    {
        return config('dashboard.unit_types') ?? [
            'Main Filter',
            'ESP',
            'DDS',
            'JPF',
        ];
    }

    /**
     * Base Query
     */
    protected function baseQuery($filters = [])
    {
        $year   = $filters['year']   ?? now()->year;
        $month  = $filters['month']  ?? null;
        $week   = $filters['week']   ?? null;
        $period = $filters['period'] ?? 'yearly';

        $unitTypes = $this->getUnitTypes();

        $query = MsoTransaction::query()
            ->whereNotNull('start_date')
            ->whereYear('start_date', $year)
            ->whereHas('nomenclature', function ($q) use ($unitTypes) {
                $q->whereIn('type', $unitTypes);
            });

        if ($period === 'monthly' && $month) {
            $query->whereMonth('start_date', $month);
        }

        if ($period === 'weekly' && $week) {
            $query->whereRaw('WEEK(start_date, 1) = ?', [$week]);
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | 1️⃣ Maintenance Summary
    |--------------------------------------------------------------------------
    */
    public function getMaintenanceSummary($filters = [])
    {
        $year   = $filters['year']   ?? now()->year;
        $month  = $filters['month']  ?? null;
        $week   = $filters['week']   ?? null;
        $period = $filters['period'] ?? 'yearly';

        $query = MsoTransaction::query()
            ->where(function ($q) use ($year, $period, $month, $week) {
                $q->whereNull('start_date')
                  ->orWhere(function ($q2) use ($year, $period, $month, $week) {
                      $q2->whereNotNull('start_date')
                         ->whereYear('start_date', $year);

                      if ($period === 'monthly' && $month) {
                          $q2->whereMonth('start_date', $month);
                      }
                      if ($period === 'weekly' && $week) {
                          $q2->whereRaw('WEEK(start_date, 1) = ?', [$week]);
                      }
                  });
            })
            ->whereHas('nomenclature', function ($q) {
                $q->whereIn('type', $this->getUnitTypes());
            });

        return $query
            ->select(
                'area_id',
                'nomenclature_id',
                'maintenance_type_id',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('area_id', 'nomenclature_id', 'maintenance_type_id')
            ->orderByDesc('total')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | 2️⃣ Pending Abnormality
    |--------------------------------------------------------------------------
    */
    public function getPendingAbnormalities($filters = [])
    {
        $year   = $filters['year']   ?? now()->year;
        $month  = $filters['month']  ?? null;
        $week   = $filters['week']   ?? null;
        $period = $filters['period'] ?? 'yearly';

        $query = MsoTransaction::query()
            ->where('status_pekerjaan', 'Open')
            ->whereYear('created_at', $year);

        if ($period === 'monthly' && $month) {
            $query->whereMonth('created_at', $month);
        }

        if ($period === 'weekly' && $week) {
            $query->whereRaw('WEEK(created_at, 1) = ?', [$week]);
        }

        return $query
            ->select(
                'area_id',
                'nomenclature_id',
                DB::raw('COUNT(*) as total_pending')
            )
            ->groupBy('area_id', 'nomenclature_id')
            ->orderByDesc('total_pending')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | 3️⃣ MSO List for Maintenance Summary (per group: area + nomenclature + type)
    |--------------------------------------------------------------------------
    */
    public function getMsoListForSummary($filters = [])
    {
        $year   = $filters['year']   ?? now()->year;
        $month  = $filters['month']  ?? null;
        $week   = $filters['week']   ?? null;
        $period = $filters['period'] ?? 'yearly';

        $query = MsoTransaction::query()
            ->where(function ($q) use ($year, $period, $month, $week) {
                // MSO tanpa start_date (Open/belum dimulai) tetap ikut
                $q->whereNull('start_date')
                  ->orWhere(function ($q2) use ($year, $period, $month, $week) {
                      $q2->whereNotNull('start_date')
                         ->whereYear('start_date', $year);

                      if ($period === 'monthly' && $month) {
                          $q2->whereMonth('start_date', $month);
                      }
                      if ($period === 'weekly' && $week) {
                          $q2->whereRaw('WEEK(start_date, 1) = ?', [$week]);
                      }
                  });
            })
            ->whereHas('nomenclature', function ($q) {
                $q->whereIn('type', $this->getUnitTypes());
            });

        return $query
            ->with(['nomenclature', 'area', 'maintenanceType'])
            ->select(
                'id',
                'no_mso',
                'area_id',
                'nomenclature_id',
                'maintenance_type_id',
                'start_date',
                'finish_date',
                'total_duration',
                'status_pekerjaan',
                'description',
            )
            ->orderByDesc('start_date')
            ->get()
            ->groupBy(function ($item) {
                return $item->area_id . '_' . $item->nomenclature_id . '_' . $item->maintenance_type_id;
            });
    }

    /*
    |--------------------------------------------------------------------------
    | 4️⃣ MSO List for Pending Abnormality (per group: area + nomenclature)
    |--------------------------------------------------------------------------
    */
    public function getMsoListForPending($filters = [])
    {
        $year   = $filters['year']   ?? now()->year;
        $month  = $filters['month']  ?? null;
        $week   = $filters['week']   ?? null;
        $period = $filters['period'] ?? 'yearly';

        $query = MsoTransaction::query()
            ->where('status_pekerjaan', 'Open')
            ->whereYear('created_at', $year);

        if ($period === 'monthly' && $month) {
            $query->whereMonth('created_at', $month);
        }

        if ($period === 'weekly' && $week) {
            $query->whereRaw('WEEK(created_at, 1) = ?', [$week]);
        }

        return $query
            ->with(['nomenclature', 'area', 'maintenanceType'])
            ->select(
                'id',
                'no_mso',
                'area_id',
                'nomenclature_id',
                'maintenance_type_id',
                'start_date',
                'finish_date',
                'total_duration',
                'status_pekerjaan',
                'description',
            )
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(function ($item) {
                return $item->area_id . '_' . $item->nomenclature_id;
            });
    }

    /*
    |--------------------------------------------------------------------------
    | 5️⃣ Top 5 Frequency (Monthly)
    |--------------------------------------------------------------------------
    */
    public function getTopFiveFrequencyMonthly($filters = [])
    {
        $year  = $filters['year']  ?? now()->year;
        $month = $filters['month'] ?? now()->month;

        return MsoTransaction::query()
            ->where('maintenance_type_id', 2)
            ->where('status_pekerjaan', 'Closed')
            ->whereMonth('start_date', $month)
            ->whereYear('start_date', $year)
            ->select(
                'nomenclature_id',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('nomenclature_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | 4️⃣ Top 5 Frequency (Yearly)
    |--------------------------------------------------------------------------
    */
    public function getTopFiveFrequencyYearly($filters = [])
    {
        $year = $filters['year'] ?? now()->year;

        return MsoTransaction::query()
            ->where('maintenance_type_id', 2)
            ->where('status_pekerjaan', 'Closed')
            ->whereYear('start_date', $year)
            ->select(
                'nomenclature_id',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('nomenclature_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | 5️⃣ Top 5 Duration (Monthly)
    |--------------------------------------------------------------------------
    */
    public function getTopFiveDurationMonthly($filters = [])
    {
        $year  = $filters['year']  ?? now()->year;
        $month = $filters['month'] ?? now()->month;

        return MsoTransaction::query()
            ->where('maintenance_type_id', 2)
            ->where('status_pekerjaan', 'Closed')
            ->whereMonth('start_date', $month)
            ->whereYear('start_date', $year)
            ->whereNotNull('total_duration')
            ->select(
                'nomenclature_id',
                DB::raw('SUM(total_duration) as total_duration')
            )
            ->groupBy('nomenclature_id')
            ->orderByDesc('total_duration')
            ->limit(5)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | 6️⃣ Top 5 Duration (Yearly)
    |--------------------------------------------------------------------------
    */
    public function getTopFiveDurationYearly($filters = [])
    {
        $year = $filters['year'] ?? now()->year;

        return MsoTransaction::query()
            ->where('maintenance_type_id', 2)
            ->where('status_pekerjaan', 'Closed')
            ->whereYear('start_date', $year)
            ->whereNotNull('total_duration')
            ->select(
                'nomenclature_id',
                DB::raw('SUM(total_duration) as total_duration')
            )
            ->groupBy('nomenclature_id')
            ->orderByDesc('total_duration')
            ->limit(5)
            ->get();
    }
}