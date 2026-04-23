<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MsoTransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicSubmissionController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ProductionCalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NomenclatureController;
use App\Http\Controllers\MaterialRequestController;
use App\Http\Controllers\MaterialMonitoringController;


Route::get('/', fn () => redirect()->route('mso.index'));

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {

    Route::resource('mso', MsoTransactionController::class);
    Route::get('/mso/{id}/export-pdf', [MsoTransactionController::class, 'exportPdf'])
        ->name('mso.export.pdf');

    // ===============================
    // 🆕 ROUTE UPDATE TIME (SUPERVISOR ONLY)
    // ===============================
    Route::put(
        '/mso/{mso}/update-time',
        [MsoTransactionController::class, 'updateTime']
    )->name('mso.update-time');

    // ===============================
    // AJAX ROUTES
    // ===============================
    Route::get('/ajax/areas/{plant_id}', [MsoTransactionController::class, 'getAreas']);
    Route::get('/ajax/nomenclatures/{area_id}', [MsoTransactionController::class, 'getNomenclatures']);

    Route::get('/ajax/nomenclature-status/{id}', function ($id) {
        $nomen = \App\Models\Nomenclature::findOrFail($id);
        return response()->json([
            'status' => $nomen->default_status
        ]);
    });

    Route::get(
        '/ajax/components/by-nomenclature/{id}',
        [AjaxController::class, 'componentsByNomenclature']
    );

    Route::put(
        '/mso-finding/{finding}/material',
        [MsoTransactionController::class, 'updateMaterialMaster']
    )->name('mso.finding.update-material');

    Route::delete(
        '/mso-finding/{finding}',
        [MsoTransactionController::class, 'destroyFinding']
    )->name('mso.finding.destroy');

    Route::resource(
        'production-calendar',
        ProductionCalendarController::class
    )->except(['show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::get('/activity-log', [ActivityLogController::class, 'index'])
    ->middleware('permission:view activity log');

Route::get('/nomenclatures/{nomenclature}/specification',
    [NomenclatureController::class, 'specification']
)->name('nomenclatures.specification');

Route::post('/nomenclatures/{nomenclature}/specification',
    [NomenclatureController::class, 'updateSpecification']
)->name('nomenclatures.specification.update');

Route::get('/nomenclatures', [NomenclatureController::class, 'index'])
    ->middleware('auth')
    ->name('nomenclatures.index');

// ===============================
// MONITORING MATERIAL ROUTES
// ===============================
Route::middleware('auth')->prefix('monitoring-material')->name('monitoring.')->group(function () {

    // Halaman utama: daftar semua data + filter material_master
    Route::get('/', [MaterialMonitoringController::class, 'index'])
        ->name('index');

    // Layar detail per ID Trans: tabel + form insert baris baru
    Route::get('/detail/{trans_id}', [MaterialMonitoringController::class, 'detail'])
        ->name('detail');

    // Simpan baris baru dari form di layar detail
    Route::post('/store', [MaterialMonitoringController::class, 'store'])
        ->name('store');

    // Update baris dari modal edit (PUT /monitoring-material/{monitoring})
    Route::put('/{monitoring}', [MaterialMonitoringController::class, 'update'])
        ->name('update');
});