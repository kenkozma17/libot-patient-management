<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\InventoryCategoryController;
use App\Http\Controllers\LabTestController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientVisitController;
use App\Models\Invoice;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/', function () {
        return Inertia::render('Home');
    });

    /** Patients */
    Route::resource('patients', PatientController::class);
    Route::resource('patient-visits', PatientVisitController::class);
    Route::get('patient-visits/create/{patientId?}', [PatientVisitController::class, 'create'])
        ->name('patient-visits.create');
    Route::post('patient-visits/upload-results/{patientVisitId}', [PatientVisitController::class, 'uploadResults'])
        ->name('patient-visits.upload-results');
    Route::delete('patient-visits/delete-file/{fileId}', [PatientVisitController::class, 'destroyFile'])
        ->name('patient-visits.destroy-file');

    /** Inventory */
    Route::resource('inventory', InventoryController::class);
    Route::resource('inventory-transactions', InventoryTransactionController::class);
    Route::resource('inventory-categories', InventoryCategoryController::class);

    /** Lab Tests */
    Route::resource('lab-tests', LabTestController::class);

});
