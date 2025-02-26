<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\InventoryCategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LabTestController;
use App\Http\Controllers\NotificationsController;
use App\Notifications\ExpirationNotice;
use App\Notifications\LowInventory;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientVisitController;
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

    Route::get('/', function () {
        // Check for expiration notice
        $inventoryItems = InventoryItem::where('days_before_expiration_limit', '>', 0)
            ->with(['transactions'])
            ->get();
        foreach($inventoryItems as $item) {
            $today = Carbon\Carbon::now();

            foreach($item->transactions as $transaction) {
                $expirationDate = Carbon\Carbon::parse($transaction->expiration_date);
                $isTodayBeforeExpiration = $today->lessThanOrEqualTo($expirationDate);
                $isDiffInDaysBeforeExpiration = $today->diffInDays($expirationDate) <= $item->days_before_expiration_limit;
                $checkedExpirationToday = $today->isSameDay($item->expiry_date_check);

                // This is null for some reason.
                // dd($item->expiry_date_check);

                if($isTodayBeforeExpiration) {
                    // dd($checkedExpirationToday);
                    if($isDiffInDaysBeforeExpiration && $checkedExpirationToday === false) {
                        // $item->notify(new ExpirationNotice($item));

                    }
                }
            }
            $item->update(['expiry_date_check' => Carbon\Carbon::now()]);
            $item->save();
        }

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
    Route::delete('patient-visits/delete-lab-test/{fileId}', [PatientVisitController::class, 'destroyLabTest'])
        ->name('patient-visits.destroy-lab-test');
    Route::put('patient-visits/update-consumption/{fileId}', [PatientVisitController::class, 'updateConsumption'])
        ->name('patient-visits.update-consumption');

    /** Inventory */
    Route::resource('inventory', InventoryController::class);
    Route::resource('inventory-transactions', InventoryTransactionController::class);
    Route::resource('inventory-categories', InventoryCategoryController::class);

    /** Invoices */
    Route::resource('invoices', InvoiceController::class);

    /** Lab Tests */
    Route::resource('lab-tests', LabTestController::class);

    /** Notifications */
    Route::resource('notifications', NotificationsController::class);
    Route::post('notifications/mark-as-read/{notificationId}', [NotificationsController::class, 'markAsRead'])
        ->name('notifications.mark-as-read');

});
