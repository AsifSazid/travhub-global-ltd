<?php

use App\Http\Controllers\CountryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageController;
use Illuminate\Support\Facades\Route;

// FRONTEND
Route::get('/package-details', function () {
    return view('frontend.package-details');
})->name('package.details');

Route::get('/packages', [HomeController::class, 'packages'])->name('fn.packages');

// Package
Route::get('/admin/packages', [PackageController::class, 'index'])->name('backend.packages.index'); // list page
Route::get('/admin/packages/{package}/edit', [PackageController::class, 'edit'])->name('backend.packages.edit'); // Edit Page
Route::get('/admin/packages/{package}/show', [PackageController::class, 'show'])->name('backend.packages.show'); // Show Page
Route::get('/admin/packages/list', [PackageController::class, 'getData'])->name('backend.packages.getData');
Route::get('/admin/packages/trash', [PackageController::class, 'trash'])->name('backend.packages.trash');

Route::get('/api/countries/{id}/cities', [CountryController::class, 'getCities']);

// Multi Form end

// Package
Route::get('/admin/packages/download/pdf', [PackageController::class, 'downloadPdf'])->name('backend.packages.download.pdf');
Route::get('/admin/packages/download/pdf/package', [PackageController::class, 'packagePdf'])->name('backend.packages.package.pdf');
Route::post('/admin/packages/{id}/restore', [PackageController::class, 'restore'])->name('backend.packages.restore');
Route::delete('/admin/packages/{id}/force-delete', [PackageController::class, 'forceDelete'])->name('backend.packages.forceDelete');


Route::prefix('admin/packages/')->name('backend.packages.')->group(function () {
    Route::get('create', [PackageController::class, 'create'])->name('create');
    Route::put('{uuid}/update', [PackageController::class, 'update'])->name('update');
    Route::post('store', [PackageController::class, 'store'])->name('store');
    Route::get('{uuid}/step/{step}', [PackageController::class, 'step'])->name('step');
    Route::post('{uuid}/step/{step}', [PackageController::class, 'stepForStore'])->name('step');
    Route::delete('/{uuid}', [PackageController::class, 'destroy'])->name('destroy');
});
