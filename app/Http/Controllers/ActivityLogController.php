<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Ambil activity log terbaru + relasi user
        $logs = Activity::with('causer')
            ->latest()
            ->paginate(20);

        return view('activity.index', compact('logs'));
    }
}
