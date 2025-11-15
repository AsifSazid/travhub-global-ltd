<?php

use App\Http\Controllers\ActivityCategoryController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CurrentRateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\InclusionController;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home'])->name('home');

// Activity Category
Route::get('/activity-categories/list', [ActivityCategoryController::class, 'getData'])->name('activity-categories.getData');
Route::get('/activity-categories/trash', [ActivityCategoryController::class, 'trash'])->name('activity-categories.trash');
// Activity
Route::get('/activities/list', [ActivityController::class, 'getData'])->name('activities.getData');
Route::get('/activities/trash', [ActivityController::class, 'trash'])->name('activities.trash');
// City
Route::get('/cities/list', [CityController::class, 'getData'])->name('cities.getData');
Route::get('/cities/trash', [CountryController::class, 'trash'])->name('cities.trash');
// Country
Route::get('/countries/list', [CountryController::class, 'getData'])->name('countries.getData');
Route::get('/countries/trash', [CountryController::class, 'trash'])->name('countries.trash');
// Currency
Route::get('/currencies/list', [CurrencyController::class, 'getData'])->name('currencies.getData');
Route::get('/currencies/trash', [CurrencyController::class, 'trash'])->name('currencies.trash');
// Current Rate
Route::get('/current_rates/list', [CurrentRateController::class, 'getData'])->name('current_rates.getData');
Route::get('/current_rates/trash', [CurrentRateController::class, 'trash'])->name('current_rates.trash');
// Hotel
Route::get('/hotels/list', [HotelController::class, 'getData'])->name('hotels.getData');
Route::get('/hotels/trash', [HotelController::class, 'trash'])->name('hotels.trash');
// Inclusion
Route::get('/inclusions/list', [InclusionController::class, 'getData'])->name('inclusions.getData');
Route::get('/inclusions/trash', [InclusionController::class, 'trash'])->name('inclusions.trash');
// Navigation
Route::get('/navigations/list', [NavigationController::class, 'getData'])->name('navigations.getData');
Route::get('/navigations/trash', [NavigationController::class, 'trash'])->name('navigations.trash');
// Role
Route::get('/roles/list', [RoleController::class, 'getData'])->name('roles.getData');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/account-info', [ProfileController::class, 'accEdit'])->name('account.edit');
    Route::patch('/account-info', [ProfileController::class, 'accUpdate'])->name('account.update');
    Route::delete('/account-info', [ProfileController::class, 'accDestroy'])->name('account.destroy');

    //User
    Route::get('/users', [ProfileController::class, 'index'])->name('users');

    Route::resources([
        // admin panel er jonno
        'activity-categories' => ActivityCategoryController::class,
        'activities' => ActivityController::class,
        'cities' => CityController::class,
        'countries' => CountryController::class,
        'currencies' => CurrencyController::class,
        'current_rates' => CurrentRateController::class,
        'hotels' => HotelController::class,
        'inclusions' => InclusionController::class,
        'navigations' => NavigationController::class,
        // 'packages' => PackageController::class,
        'roles' => RoleController::class,

    ]);

    // Activity Category
    Route::get('/activity-categories/download/pdf', [ActivityCategoryController::class, 'downloadPdf'])->name('activity-categories.download.pdf');
    Route::post('/activity-categories/{id}/restore', [ActivityCategoryController::class, 'restore'])->name('activity-categories.restore');
    Route::delete('/activity-categories/{id}/force-delete', [ActivityCategoryController::class, 'forceDelete'])->name('activity-categories.forceDelete');
    // Activity
    Route::get('/activities/download/pdf', [ActivityController::class, 'downloadPdf'])->name('activities.download.pdf');
    Route::post('/activities/{id}/restore', [ActivityController::class, 'restore'])->name('activities.restore');
    Route::delete('/activities/{id}/force-delete', [ActivityController::class, 'forceDelete'])->name('activities.forceDelete');
    // City
    Route::get('/cities/download/pdf', [CityController::class, 'downloadPdf'])->name('cities.download.pdf');
    Route::post('/cities/{id}/restore', [CityController::class, 'restore'])->name('cities.restore');
    Route::delete('/cities/{id}/force-delete', [CityController::class, 'forceDelete'])->name('cities.forceDelete');
    // Country
    Route::get('/countries/download/pdf', [CountryController::class, 'downloadPdf'])->name('countries.download.pdf');
    Route::post('/countries/{id}/restore', [CountryController::class, 'restore'])->name('countries.restore');
    Route::delete('/countries/{id}/force-delete', [CountryController::class, 'forceDelete'])->name('countries.forceDelete');
    // Currency
    Route::get('/currencies/download/pdf', [CurrencyController::class, 'downloadPdf'])->name('currencies.download.pdf');
    Route::post('/currencies/{id}/restore', [CurrencyController::class, 'restore'])->name('currencies.restore');
    Route::delete('/currencies/{id}/force-delete', [CurrencyController::class, 'forceDelete'])->name('currencies.forceDelete');
    // Current Rate
    Route::get('/current_rates/download/pdf', [CurrentRateController::class, 'downloadPdf'])->name('current_rates.download.pdf');
    Route::post('/current_rates/{id}/restore', [CurrentRateController::class, 'restore'])->name('current_rates.restore');
    Route::delete('/current_rates/{id}/force-delete', [CurrentRateController::class, 'forceDelete'])->name('current_rates.forceDelete');
    // Hotel
    Route::get('/hotels/download/pdf', [HotelController::class, 'downloadPdf'])->name('hotels.download.pdf');
    Route::post('/hotels/{id}/restore', [HotelController::class, 'restore'])->name('hotels.restore');
    Route::delete('/hotels/{id}/force-delete', [HotelController::class, 'forceDelete'])->name('hotels.forceDelete');
    // Inclusion
    Route::get('/inclusions/download/pdf', [InclusionController::class, 'downloadPdf'])->name('inclusions.download.pdf');
    Route::post('/inclusions/{id}/restore', [InclusionController::class, 'restore'])->name('inclusions.restore');
    Route::delete('/inclusions/{id}/force-delete', [InclusionController::class, 'forceDelete'])->name('inclusions.forceDelete');
    // Navigation
    Route::get('/navigations/sidebar', [NavigationController::class, 'getSidebarNavigation'])->name('navigations.getSidebarNavigation');
    Route::get('/navigations/download/pdf', [NavigationController::class, 'downloadPdf'])->name('navigations.download.pdf');
    Route::post('/navigations/{id}/restore', [NavigationController::class, 'restore'])->name('navigations.restore');
    Route::delete('/navigations/{id}/force-delete', [NavigationController::class, 'forceDelete'])->name('navigations.forceDelete');
    Route::get('/navigations/sync-routes', [NavigationController::class, 'syncRoutes'])
        ->name('navigations.syncRoutes');
    // Role
    // Route::get('/roles/list', [RoleController::class, 'getData'])->name('roles.getData');
    Route::get('/roles/download/pdf', [RoleController::class, 'downloadPdf'])->name('roles.download.pdf');
});


Route::post('/api/cities/store', [CityController::class, 'apiStore'])->name('api.cities.store');

require __DIR__ . '/auth.php';
