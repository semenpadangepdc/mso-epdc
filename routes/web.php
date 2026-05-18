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
use App\Http\Controllers\MaterialMasterController;
use App\Http\Controllers\Admin\UserController;   // ← TAMBAHAN

Route::get('/', fn () => redirect()->route('mso.index'));

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {

    Route::get('/mso/export-excel', [MsoTransactionController::class, 'exportExcel'])->name('mso.export-excel');
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

Route::post('/nomenclatures/{nomenclature}/add-component',
    [NomenclatureController::class, 'addComponent']
)->name('nomenclatures.add-component')->middleware('auth');

Route::get('/nomenclatures', [NomenclatureController::class, 'index'])
    ->middleware('auth')
    ->name('nomenclatures.index');

// ===============================
// MONITORING MATERIAL ROUTES
// ===============================
Route::middleware('auth')->prefix('monitoring-material')->name('monitoring.')->group(function () {

    Route::get('/', [MaterialMonitoringController::class, 'index'])
        ->name('index');

    Route::get('/resume', [MaterialMonitoringController::class, 'resume'])
        ->name('resume');

    Route::get('/export/{trans_id}', [MaterialMonitoringController::class, 'export'])
        ->name('export');

    Route::get('/detail/{trans_id}', [MaterialMonitoringController::class, 'detail'])
        ->name('detail');

    Route::post('/store', [MaterialMonitoringController::class, 'store'])
        ->name('store');

    Route::get('/export-excel', [MaterialMonitoringController::class, 'exportExcel'])
        ->name('export-excel');

    Route::get('/detail-export/{trans_id}', [MaterialMonitoringController::class, 'exportDetailExcel'])
        ->name('export-detail');

    Route::put('/{monitoring}', [MaterialMonitoringController::class, 'update'])
        ->name('update');
});

// ===============================
// 🔐 MATERIAL MASTER ROUTES (ADMIN ONLY)
// ===============================
Route::middleware(['auth', 'role:Admin'])
    ->prefix('material-master')
    ->name('material-master.')
    ->group(function () {
        Route::get('/',                        [MaterialMasterController::class, 'index'])->name('index');
        Route::get('/create',                  [MaterialMasterController::class, 'create'])->name('create');
        Route::post('/',                       [MaterialMasterController::class, 'store'])->name('store');
        Route::get('/{materialMaster}/edit',   [MaterialMasterController::class, 'edit'])->name('edit');
        Route::put('/{materialMaster}',        [MaterialMasterController::class, 'update'])->name('update');
        Route::delete('/{materialMaster}',     [MaterialMasterController::class, 'destroy'])->name('destroy');
    });

// ===============================
// 🔐 ADMIN USER MANAGEMENT (ADMIN ONLY)
// ===============================
Route::middleware(['auth', 'role:Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', UserController::class);
    });