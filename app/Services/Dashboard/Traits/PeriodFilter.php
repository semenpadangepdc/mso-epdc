<?php

namespace App\Services\Dashboard\Traits;

trait PeriodFilter
{
    protected function applyPeriodFilter($query, array $filters)
    {
        $year  = $filters['year'] ?? now()->year;
        $month = $filters['month'] ?? null;
        $week  = $filters['week'] ?? null;
        $period = $filters['period'] ?? 'yearly';

        $query->whereYear('mso_transactions.start_date', $year);

        if ($period === 'monthly' && $month) {
            $query->whereMonth('mso_transactions.start_date', $month);
        }

        if ($period === 'weekly' && $week) {
            $query->whereRaw('WEEK(mso_transactions.start_date, 1) = ?', [$week]);
        }

        // cumulative until week X
        if ($period !== 'weekly' && $week) {
            $query->whereRaw('WEEK(mso_transactions.start_date, 1) <= ?', [$week]);
        }

        return $query;
    }
}