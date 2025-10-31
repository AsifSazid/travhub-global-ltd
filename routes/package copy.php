<?php

use App\Http\Controllers\CountryController;
use App\Http\Controllers\PackageController;
use Illuminate\Support\Facades\Route;

// Package
Route::get('/packages/list', [PackageController::class, 'getData'])->name('packages.getData');
Route::get('/packages/trash', [PackageController::class, 'trash'])->name('packages.trash');

// Multi Form Start
// Route::get('/packages/create-multi', function(){ return view('backend.packages.create-multistep'); })->name('packages.create');
// Route::get('/packages/create', [PackageController::class, 'create'])->name('packages.create');


// Route::post('/packages/step-1', [PackageController::class,'stepOne'])->name('packages.step1');
// Route::post('/packages/step-2', [PackageController::class,'stepTwo'])->name('packages.step2');
// Route::post('/packages/step-3', [PackageController::class,'stepThree'])->name('packages.step3');
// Route::post('/packages/step-4', [PackageController::class,'stepFour'])->name('packages.step4');
// Route::post('/packages/step-5', [PackageController::class,'stepFive'])->name('packages.step5');
// Route::post('/packages/step-6', [PackageController::class,'stepSix'])->name('packages.step6');

Route::get('/packages', [PackageController::class, 'index'])->name('packages.index'); // list page

Route::prefix('packages')->name('packages.')->group(function () {
    Route::get('create', [PackageController::class, 'create'])->name('create');
    Route::post('step/{step}', [PackageController::class, 'step'])->name('step');
});

Route::get('/api/countries/{id}/cities', [CountryController::class, 'getCities']);

// Multi Form end

// Package
Route::get('/packages/download/pdf', [PackageController::class, 'downloadPdf'])->name('packages.download.pdf');
Route::post('/packages/{id}/restore', [PackageController::class, 'restore'])->name('packages.restore');
Route::delete('/packages/{id}/force-delete', [PackageController::class, 'forceDelete'])->name('packages.forceDelete');
