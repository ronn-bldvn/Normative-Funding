<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FundingController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\GraduatesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Mirrors the Flask route structure:
|
|   Flask                              →  Laravel
|   ─────────────────────────────────────────────────────────────────────
|   GET /                              →  FundingController@index
|   GET /api/income-data               →  FundingController@getIncomeData
|   GET /api/allotment-data            →  FundingController@getAllotmentData
|   GET /api/expenditure-data          →  FundingController@getExpenditureData
|
|   GET /suc-faculty                   →  FacultyController@index
|   GET /api/faculty-pie               →  FacultyController@facultyPie
|
|   GET /graduates                     →  GraduatesController@index
|   GET /api/graduates-summary         →  GraduatesController@summary
|   GET /api/graduates-by-college      →  GraduatesController@byCollege
|   GET /api/graduates-gender-by-college → GraduatesController@genderByCollege
|   GET /api/graduates-by-program      →  GraduatesController@byProgram
*/

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

Route::prefix('api')->name('api.graduates.')->group(function () {
    Route::get('graduates-summary',           [GraduatesController::class, 'summary'])        ->name('summary');
    Route::get('graduates-by-college',        [GraduatesController::class, 'byCollege'])      ->name('by-college');
    Route::get('graduates-gender-by-college', [GraduatesController::class, 'genderByCollege'])->name('gender-by-college');
    Route::get('graduates-by-program',        [GraduatesController::class, 'byProgram'])      ->name('by-program');
});
