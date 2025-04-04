<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\InventoryCategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LabTestController;
use App\Http\Controllers\LoanPaymentController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PatientLoansController;
use App\Notifications\ExpirationNotice;
use App\Notifications\LowInventory;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientVisitController;
use App\Http\Controllers\ReportsController;
use App\Models\InventoryItem;
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

    Route::resource('/', HomeController::class);

    /** Patients */
    Route::resource('patients', PatientController::class);
    Route::resource('patient-visits', PatientVisitController::class);
    Route::get('patient-visits/create/{patientId?}', [PatientVisitController::class, 'create'])
        ->name('patient-visits.create');
    Route::post('patient-visits/upload-results/{patientVisitId}', [PatientVisitController::class, 'uploadResults'])
        ->name('patient-visits.upload-results');
    Route::delete('patient-visits/delete-file/{fileId}', [PatientVisitController::class, 'destroyFile'])
        ->name('patient-visits.destroy-file');
    Route::delete('patient-visits/delete-lab-test/{fileId}', [PatientVisitController::class, 'destroyLabTest'])
        ->name('patient-visits.destroy-lab-test');
    Route::put('patient-visits/update-consumption/{fileId}', [PatientVisitController::class, 'updateConsumption'])
        ->name('patient-visits.update-consumption');
    Route::post('patient-visits/mark-as-paid', [PatientVisitController::class, 'markAsPaid'])
        ->name('patient-visits.mark-as-paid');

    /** Inventory */
    Route::resource('inventory', InventoryController::class);
    Route::resource('inventory-transactions', InventoryTransactionController::class);
    Route::get('inventory-transactions/lot/{lotNumber}', [InventoryTransactionController::class, 'getLotListing'])
        ->name('inventory-transactions.lot-listing');
    Route::post('inventory-transactions/lot/{lotNumber}', [InventoryTransactionController::class, 'archiveLot'])
        ->name('inventory-transactions.archive-lot');
    Route::resource('inventory-categories', InventoryCategoryController::class);

    /** Invoices */
    Route::resource('invoices', InvoiceController::class);

    /** Lab Tests */
    Route::resource('lab-tests', LabTestController::class);

    /** Notifications */
    Route::resource('notifications', NotificationsController::class);
    Route::post('notifications/mark-as-read/{notificationId}', [NotificationsController::class, 'markAsRead'])
        ->name('notifications.mark-as-read');

    /** Reports */
    Route::get('reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('reports/generate', [ReportsController::class, 'generateReport'])
        ->name('reports.generate');
    Route::get('reports/generate-loan-report/{loanId}', [ReportsController::class, 'generateLoanReport'])
        ->name('reports.generate-loan-report');

    /** Loans */
    Route::resource('patient-loans', PatientLoansController::class);
    Route::get('patient-loans/create/{patientId?}', [PatientLoansController::class, 'create'])
        ->name('patient-loans.create');
    Route::resource('loan-payments', LoanPaymentController::class);
    Route::get('loan-payments/create/{loanId?}', [LoanPaymentController::class, 'create'])
        ->name('loan-payments.create');

});
