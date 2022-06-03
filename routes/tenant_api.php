<?php

use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\Charge\{PaymentController, ReceiveController};
use App\Http\Controllers\Api\RecurrenceController;
use App\Http\Controllers\Api\Relationship\{CustomerController, SupplierController};
use Illuminate\Support\Facades\Route;

Route::prefix('relationship')->group(function () {
    Route::resource('customer', CustomerController::class);
    Route::resource('supplier', SupplierController::class);
});
Route::resource('recurrence', RecurrenceController::class);
Route::resource('bank', BankController::class);

Route::prefix('charge')->as('charge.')->group(function () {
    Route::resource('receive', ReceiveController::class);
    Route::post('receive/{id}/pay', [ReceiveController::class, 'pay']);
    Route::get('receive/{type}/resume', [ReceiveController::class, 'resume'])->name('receive.resume');

    Route::resource('payment', PaymentController::class);
    Route::post('payment/{id}/pay', [PaymentController::class, 'pay']);
    Route::get('payment/{type}/resume', [ReceiveController::class, 'resume'])->name('payment.resume');
});