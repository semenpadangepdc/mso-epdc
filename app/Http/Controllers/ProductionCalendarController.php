<?php

namespace App\Http\Controllers;

use App\Models\ProductionCalendar;
use App\Models\Plant;
use App\Models\Area;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductionCalendarController extends Controller
{
    public function index()
    {
        $calendars = ProductionCalendar::with(['plant','area'])
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->paginate(15);

        return view('production-calendar.index', compact('calendars'));
    }

    public function create()
    {
        return view('production-calendar.create', [
            'plants' => Plant::all(),
            'areas'  => Area::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'plant_id'              => 'required|exists:plants,id',
            'area_id'               => 'required|exists:areas,id',
            'year'                  => 'required|integer',
            'month'                 => 'required|integer|min:1|max:12',
            'total_days'            => 'required|integer|min:1|max:31',
            'planned_running_days'  => 'required|integer|min:0',
            'planned_shutdown_days' => 'required|integer|min:0',
        ]);

        // Validasi logika hari
        if (
            $request->planned_running_days +
            $request->planned_shutdown_days
            > $request->total_days
        ) {
            return back()
                ->withErrors('Total hari jalan + stop terencana melebihi jumlah hari bulan')
                ->withInput();
        }

        // Konversi ke jam
        $plannedProductionHours = $request->planned_running_days * 24;
        $plannedDowntimeHours   = $request->planned_shutdown_days * 24;

        ProductionCalendar::updateOrCreate(
            [
                'plant_id' => $request->plant_id,
                'area_id'  => $request->area_id,
                'year'     => $request->year,
                'month'    => $request->month,
            ],
            [
                'total_days'               => $request->total_days,
                'planned_running_days'     => $request->planned_running_days,
                'planned_shutdown_days'    => $request->planned_shutdown_days,
                'planned_production_hours' => $plannedProductionHours,
                'planned_downtime_hours'   => $plannedDowntimeHours,
            ]
        );

        return redirect()
            ->route('production-calendar.index')
            ->with('success', 'Production Calendar berhasil disimpan');
    }

    public function edit(string $id)
    {
        $calendar = ProductionCalendar::with(['plant', 'area'])->findOrFail($id);

        return view('production-calendar.edit', [
            'calendar' => $calendar,
            'plants'   => Plant::all(),
            'areas'    => Area::where('plant_id', $calendar->plant_id)->get(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $calendar = ProductionCalendar::findOrFail($id);

        $request->validate([
            'plant_id'              => 'required|exists:plants,id',
            'area_id'               => 'required|exists:areas,id',
            'year'                  => 'required|integer',
            'month'                 => 'required|integer|min:1|max:12',
            'total_days'            => 'required|integer|min:1|max:31',
            'planned_running_days'  => 'required|integer|min:0',
            'planned_shutdown_days' => 'required|integer|min:0',
        ]);

        // Validasi logika hari
        if (
            $request->planned_running_days +
            $request->planned_shutdown_days
            > $request->total_days
        ) {
            return back()
                ->withErrors('Total hari jalan + stop terencana melebihi jumlah hari bulan')
                ->withInput();
        }

        // Konversi ke jam
        $plannedProductionHours = $request->planned_running_days * 24;
        $plannedDowntimeHours   = $request->planned_shutdown_days * 24;

        $calendar->update([
            'plant_id'                 => $request->plant_id,
            'area_id'                  => $request->area_id,
            'year'                     => $request->year,
            'month'                    => $request->month,
            'total_days'               => $request->total_days,
            'planned_running_days'     => $request->planned_running_days,
            'planned_shutdown_days'    => $request->planned_shutdown_days,
            'planned_production_hours' => $plannedProductionHours,
            'planned_downtime_hours'   => $plannedDowntimeHours,
        ]);

        return redirect()
            ->route('production-calendar.index')
            ->with('success', 'Production Calendar berhasil diperbarui');
    }
}