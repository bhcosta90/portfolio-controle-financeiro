<?php

use App\Http\Controllers\Web\BankAccountController;
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

Route::prefix('charge')->group(function () {
    Route::resource('receive', ReceiveController::class);
    Route::post('receive/{id}/pay', [ReceiveController::class, 'pay']);

    Route::resource('payment', PaymentController::class);
    Route::post('payment/{id}/pay', [PaymentController::class, 'pay']);
});