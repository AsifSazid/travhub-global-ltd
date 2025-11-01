<?php

use App\Http\Controllers\CountryController;
use App\Http\Controllers\PackageController;
use Illuminate\Support\Facades\Route;

// Package
Route::get('/packages', [PackageController::class, 'index'])->name('packages.index'); // list page
Route::get('/packages/{package}', [PackageController::class, 'edit'])->name('packages.edit'); // Edit Page
Route::get('/packages/list', [PackageController::class, 'getData'])->name('packages.getData');
Route::get('/packages/trash', [PackageController::class, 'trash'])->name('packages.trash');

Route::get('/api/countries/{id}/cities', [CountryController::class, 'getCities']);

// Multi Form end

// Package
Route::get('/packages/download/pdf', [PackageController::class, 'downloadPdf'])->name('packages.download.pdf');
Route::get('/packages/download/pdf/package', [PackageController::class, 'packagePdf'])->name('packages.package.pdf');
Route::post('/packages/{id}/restore', [PackageController::class, 'restore'])->name('packages.restore');
Route::delete('/packages/{id}/force-delete', [PackageController::class, 'forceDelete'])->name('packages.forceDelete');




Route::prefix('packages')->name('packages.')->group(function () {
    Route::get('create', [PackageController::class, 'create'])->name('create');
    Route::post('store', [PackageController::class, 'store'])->name('store');
    Route::get('{uuid}/step/{step}', [PackageController::class, 'step'])->name('step');
    Route::post('{uuid}/step/{step}', [PackageController::class, 'stepForStore'])->name('step');
});