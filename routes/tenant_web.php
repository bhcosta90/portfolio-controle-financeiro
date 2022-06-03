<?php

use App\Http\Controllers\Web\Relationship\CustomerController;
use Illuminate\Support\Facades\Route;

Route::prefix('relationship')->group(function () {
    Route::resource('customer', CustomerController::class);
    Route::resource('supplier', SupplierController::class);
});
Route::resource('recurrence', RecurrenceController::class);
Route::resource('bank', BankController::class);

Route::prefix('charge')->group(function () {
    Route::resource('receive', ReceiveController::class);
    Route::post('receive/{id}/pay', [ReceiveController::class, 'pay']);

    Route::resource('payment', PaymentController::class);
    Route::post('payment/{id}/pay', [PaymentController::class, 'pay']);
});