<?php

namespace App\Services\Dashboard;

use App\Models\MsoTransaction;
use Illuminate\Support\Facades\DB;

class AbnormalityService
{
    protected $allowedTypes = ['Main Filter','ESP','DDS','JPF'];

    public function getData($filters)
    {
        $year = $filters['year'] ?? now()->year;

        return MsoTransaction::query()
            ->join('nomenclatures', 'nomenclatures.id', '=', 'mso_transactions.nomenclature_id')
            ->whereYear('start_date', $year)
            ->where('status_pekerjaan', 'Open')
            ->whereIn('nomenclatures.type', $this->allowedTypes)
            ->select(
                'nomenclatures.area_id',
                'nomenclatures.name',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('nomenclatures.area_id','nomenclatures.name')
            ->get();
    }
}