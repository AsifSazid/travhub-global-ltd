<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// City
Route::get('/cities/list', [CityController::class, 'getData'])->name('cities.getData');
Route::get('/cities/trash', [CountryController::class, 'trash'])->name('cities.trash');
// Country
Route::get('/countries/list', [CountryController::class, 'getData'])->name('countries.getData');
Route::get('/countries/trash', [CountryController::class, 'trash'])->name('countries.trash');

Route::get('/roles/list', [RoleController::class, 'getData'])->name('roles.getData');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resources([
        // admin panel er jonno
        'cities' => CityController::class,
        'countries' => CountryController::class,
        'navigations' => NavigationController::class,
        'roles' => RoleController::class,

    ]);

    // City
    Route::get('/cities/download/pdf', [CityController::class, 'downloadPdf'])->name('cities.download.pdf');
    Route::post('/cities/{id}/restore', [CityController::class, 'restore'])->name('cities.restore');
    Route::delete('/cities/{id}/force-delete', [CityController::class, 'forceDelete'])->name('cities.forceDelete');
    // Country
    Route::get('/countries/download/pdf', [CountryController::class, 'downloadPdf'])->name('countries.download.pdf');
    Route::post('/countries/{id}/restore', [CountryController::class, 'restore'])->name('countries.restore');
    Route::delete('/countries/{id}/force-delete', [CountryController::class, 'forceDelete'])->name('countries.forceDelete');
    // Navigation
    Route::get('/navigations/sidebar', [NavigationController::class, 'getSidebarNavigation'])->name('navigations.getSidebarNavigation');
    Route::get('/navigations/list', [NavigationController::class, 'getData'])->name('navigations.getData');
    Route::get('/navigations/download/pdf', [NavigationController::class, 'downloadPdf'])->name('navigations.download.pdf');
    Route::get('/navigations/trash', [NavigationController::class, 'trash'])->name('navigations.trash');
    Route::post('/navigations/{id}/restore', [NavigationController::class, 'restore'])->name('navigations.restore');
    Route::delete('/navigations/{id}/force-delete', [NavigationController::class, 'forceDelete'])->name('navigations.forceDelete');
    Route::get('/navigations/sync-routes', [NavigationController::class, 'syncRoutes'])
        ->name('navigations.syncRoutes');
    // Role
    // Route::get('/roles/list', [RoleController::class, 'getData'])->name('roles.getData');
    Route::get('/roles/download/pdf', [RoleController::class, 'downloadPdf'])->name('roles.download.pdf');
});

require __DIR__ . '/auth.php';
