<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProductionCalendar extends Model
{
    protected $fillable = [
        'plant_id',
        'area_id',
        'year',
        'month',
        'total_days',
        'planned_running_days',
        'planned_shutdown_days',
        'planned_production_hours',
        'planned_downtime_hours',
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function getPeriodAttribute()
    {
        return Carbon::create($this->year, $this->month, 1);
    }
}