<?php

namespace App\Services\Dashboard;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    protected $quantityService;
    protected $reliabilityService;
    protected $abnormalityService;

    public function __construct(
        QuantityService $quantityService,
        ReliabilityService $reliabilityService,
        AbnormalityService $abnormalityService
    ) {
        $this->quantityService    = $quantityService;
        $this->reliabilityService = $reliabilityService;
        $this->abnormalityService = $abnormalityService;
    }

    public function getDashboardData($filters)
    {
        // Cache key includes all filters so different filter combos never share cache
        $key = 'dashboard_' . md5(json_encode($filters));

        return Cache::remember($key, now()->addMinutes(10), function () use ($filters) {

            return [
                'availability' => $this->reliabilityService->getData($filters),
                'breakdown'    => $this->abnormalityService->getData($filters),
                'quantity'     => $this->quantityService->getData($filters),
            ];
        });
    }
}