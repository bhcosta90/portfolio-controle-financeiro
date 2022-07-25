<?php

use App\Http\Controllers\Api\Admin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::as('api.')->group(function(){
    Route::as('charge.')->prefix('charge')->group(function () {
        Route::as('payment.')->prefix('payment')->group(function () {
            Route::get('quantity/all', [Admin\Charge\Payment\ChargeController::class, 'quantityToday'])->name('quantity.today');
            Route::get('value/month', [Admin\Charge\Payment\ChargeController::class, 'valueMonth'])->name('value.month');
            Route::get('value/all', [Admin\Charge\Payment\ChargeController::class, 'valueAll'])->name('value.all');
            Route::get('due-date', [Admin\Charge\Payment\ChargeController::class, 'dueDate'])->name('due.date');    
        });
        Route::as('receive.')->prefix('receive')->group(function () {
            Route::get('quantity/all', [Admin\Charge\Receive\ChargeController::class, 'quantityToday'])->name('quantity.today');
            Route::get('value/month', [Admin\Charge\Receive\ChargeController::class, 'valueMonth'])->name('value.month');
            Route::get('value/all', [Admin\Charge\Receive\ChargeController::class, 'valueAll'])->name('value.all');
            Route::get('due-date', [Admin\Charge\Receive\ChargeController::class, 'dueDate'])->name('due.date');    
        });
    });

    Route::as('account.')->prefix('account')->group(function () {
        Route::get('bank/financial', [Admin\Account\BankController::class, 'financial'])->name('bank.financial');
    });

    Route::get('report/month', [Admin\ReportController::class, 'month'])->name('report.month');
});