<?php

use App\Http\Controllers\Web\BankAccountController;
use App\Http\Controllers\Web\Charge\PaymentController;
use App\Http\Controllers\Web\Charge\ReceiveController;
use App\Http\Controllers\Web\RecurrenceController;
use App\Http\Controllers\Web\Relationship\CustomerController;
use App\Http\Controllers\Web\Relationship\SupplierController;
use Illuminate\Support\Facades\Route;

Route::prefix('relationship')->group(function () {
    Route::resource('customer', CustomerController::class);
    Route::resource('supplier', SupplierController::class);
});
Route::resource('bank', BankAccountController::class);
Route::resource('recurrence', RecurrenceController::class);

Route::prefix('charge')->as('charge.')->group(function () {
    Route::resource('receive', ReceiveController::class);
    Route::get('receive/{id}/pay', [ReceiveController::class, 'payShow'])->name('receive.pay.show');
    Route::post('receive/{id}/pay', [ReceiveController::class, 'payStore'])->name('receive.pay.store');

    Route::resource('payment', PaymentController::class);
    Route::get('payment/{id}/pay', [PaymentController::class, 'payShow'])->name('payment.pay.show');
    Route::post('payment/{id}/pay', [PaymentController::class, 'payStore'])->name('payment.pay.store');
});