<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryTransactionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientVisitController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Home');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    /** Patients */
    Route::resource('patients', PatientController::class);
    Route::resource('patient-visits', PatientVisitController::class);
    Route::get('patient-visits/create/{patientId?}', [PatientVisitController::class, 'create'])
        ->name('patient-visits.create');

    /** Inventory */
    Route::resource('inventory', InventoryController::class);
    Route::resource('inventory-transactions', InventoryTransactionController::class);

});
