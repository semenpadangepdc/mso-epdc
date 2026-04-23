<?php

namespace App\Services\Dashboard;

use App\Models\MsoTransaction;
use Illuminate\Support\Facades\DB;

class QuantityService
{
    protected $allowedTypes = ['Main Filter','ESP','DDS','JPF'];

    public function getData($filters)
    {
        $year  = $filters['year'] ?? now()->year;
        $month = $filters['month'] ?? null;
        $plantId = $filters['plant_id'] ?? null;
        $areaId  = $filters['area_id'] ?? null;

        $query = MsoTransaction::query()
            ->select(
                'mso_transactions.maintenance_type_id',
                'mso_transactions.nomenclature_id',
                'nomenclatures.area_id',
                DB::raw('COUNT(*) as total')
            )
            ->join('nomenclatures', 'nomenclatures.id', '=', 'mso_transactions.nomenclature_id')
            ->whereYear('start_date', $year)
            ->whereIn('nomenclatures.type', $this->allowedTypes)
            ->when($month, fn($q) => $q->whereMonth('start_date', $month))
            ->when($plantId, fn($q) => $q->where('nomenclatures.plant_id', $plantId))
            ->when($areaId, fn($q) => $q->where('nomenclatures.area_id', $areaId))
            ->groupBy(
                'mso_transactions.maintenance_type_id',
                'mso_transactions.nomenclature_id',
                'nomenclatures.area_id'
            )
            ->get();

        return $this->mapByCategory($query);
    }

    private function mapByCategory($data)
    {
        return [
            'basic'      => $data->where('maintenance_type_id', 1)->values()->toArray(),
            'corrective' => $data->where('maintenance_type_id', 2)->values()->toArray(),
            'overhaul'   => $data->where('maintenance_type_id', 3)->values()->toArray(),
            'level2'     => $data->where('maintenance_type_id', 4)->values()->toArray(),
        ];
    }
}