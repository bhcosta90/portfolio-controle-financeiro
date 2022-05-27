<?php

use App\Http\Controllers\Admin\{BankController, CustomerController, FinancialBalanceController, PaymentController, RecurrenceController, SupplierController};
use App\Http\Controllers\Admin\Charge\{ChargeController, ChargeReceiveController, ChargePaymentController};
use Illuminate\Support\Facades\{Route, Auth};

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::resource('supplier', SupplierController::class);
    Route::resource('customer', CustomerController::class);
    Route::resource('bank', BankController::class);
    Route::resource('recurrence', RecurrenceController::class);
    Route::get('payment/financial-balance', [FinancialBalanceController::class, 'resume'])->name('payment.financial-balance');
    Route::get('payment/profit-month', [FinancialBalanceController::class, 'profit'])->name('payment.profit-month');
    Route::get('payment/calcule', [FinancialBalanceController::class, 'calcule'])->name('payment.calcule');

    Route::prefix('charge')->as('charge.')->group(function(){
        Route::resource('receive', ChargeReceiveController::class);
        Route::get('receive/{uuid}/pay', [ChargeReceiveController::class, 'payShow'])->name('receive.pay.show');
        Route::post('receive/{uuid}/pay', [ChargeReceiveController::class, 'payStore'])->name('receive.pay.store');
        Route::get('receive/resume/{type}', [ChargeReceiveController::class, 'resume'])->name('receive.resume');

        Route::resource('payment', ChargePaymentController::class);
        Route::get('payment/{uuid}/pay', [ChargePaymentController::class, 'payShow'])->name('payment.pay.show');
        Route::post('payment/{uuid}/pay', [ChargePaymentController::class, 'payStore'])->name('payment.pay.store');
        Route::get('payment/resume/{type}', [ChargePaymentController::class, 'resume'])->name('payment.resume');

        Route::get('{type}/resume', [ChargeController::class, 'resume'])->name('resume');
    });
    
    Route::get('payment/{type}/resume', [PaymentController::class, 'resume'])->name('payment.resume');
});
