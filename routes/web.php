<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FundingController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\GraduatesController;
use Illuminate\Support\Facades\DB;
use App\Models\FundReport;


// ──────────────────────────────────────────────────────────────────────────────
// NORMATIVE FUNDING BREAKDOWN
// ──────────────────────────────────────────────────────────────────────────────

Route::get('/', [FundingController::class, 'index'])->name('dashboard');

Route::prefix('api')->name('api.')->group(function () {
    Route::get('income-data',     [FundingController::class, 'getIncomeData'])    ->name('income');
    Route::get('allotment-data',  [FundingController::class, 'getAllotmentData']) ->name('allotment');
    Route::get('expenditure-data',[FundingController::class, 'getExpenditureData'])->name('expenditure');
});

// ──────────────────────────────────────────────────────────────────────────────
// SUC FACULTY
// ──────────────────────────────────────────────────────────────────────────────

Route::get('/suc-faculty', [FacultyController::class, 'index'])->name('suc-faculty');

Route::get('/api/faculty-pie', [FacultyController::class, 'facultyPie'])->name('api.faculty-pie');

// ──────────────────────────────────────────────────────────────────────────────
// GRADUATES
// ──────────────────────────────────────────────────────────────────────────────

Route::get('/graduates', [GraduatesController::class, 'index'])->name('graduates');

Route::prefix('api/graduates')->name('api.graduates.')->group(function () {
    Route::get('/filters', [GraduatesController::class, 'filters'])->name('filters');
    Route::get('/dashboard', [GraduatesController::class, 'dashboard'])->name('dashboard');
});


Route::get('/debug-db', function () {
    return [
        'database' => DB::connection()->getDatabaseName(),
        'host' => DB::connection()->getConfig('host'),
        'port' => DB::connection()->getConfig('port'),
        'count' => FundReport::count(),
        'years' => FundReport::select('year')->distinct()->pluck('year'),
    ];
});
