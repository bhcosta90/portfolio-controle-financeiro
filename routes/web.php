<?php

use App\Http\Controllers\Admin\Web;
use Illuminate\Support\Facades\{Auth, Route};

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

Route::redirect('/', '/home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::as('admin.')->prefix('admin')->middleware('auth')->group(function () {
    Route::as('relationship.')->prefix('relationship')->group(function () {
        Route::resource('customer', Web\Relationship\CustomerController::class);
        Route::resource('company', Web\Relationship\CompanyController::class);
    });

    Route::as('charge.')->prefix('charge')->group(function () {
        Route::get('receive/quantity/all', [Web\Charge\ChargePaymentController::class, 'quantityAll'])->name('receive.quantity.all');
        Route::get('receive/quantity/today', [Web\Charge\ChargePaymentController::class, 'quantityToday'])->name('receive.quantity.today');
        Route::get('receive/value/month', [Web\Charge\ChargeReceiveController::class, 'valueMonth'])->name('receive.value.month');
        Route::get('receive/value/all', [Web\Charge\ChargeReceiveController::class, 'valueAll'])->name('receive.value.all');
        Route::get('receive/due-date', [Web\Charge\ChargeReceiveController::class, 'dueDate'])->name('receive.due.date');

        Route::get('payment/quantity/all', [Web\Charge\ChargePaymentController::class, 'quantityAll'])->name('payment.quantity.all');
        Route::get('payment/quantity/today', [Web\Charge\ChargePaymentController::class, 'quantityToday'])->name('payment.quantity.today');
        Route::get('payment/value/month', [Web\Charge\ChargePaymentController::class, 'valueMonth'])->name('payment.value.month');
        Route::get('payment/value/all', [Web\Charge\ChargePaymentController::class, 'valueAll'])->name('payment.value.all');
        Route::get('payment/due-date', [Web\Charge\ChargePaymentController::class, 'dueDate'])->name('payment.due.date');

        Route::resource('recurrence', Web\Charge\RecurrenceController::class);
        Route::resource('receive', Web\Charge\ChargeReceiveController::class);
        Route::resource('payment', Web\Charge\ChargePaymentController::class);

        Route::get('receive/{id}/pay', [Web\Charge\ChargeReceiveController::class, 'payShow'])->name('receive.pay.show');
        Route::post('receive/{id}/pay/total', [Web\Charge\ChargeReceiveController::class, 'payTotalStore'])->name('receive.pay.total.store');
        Route::post('receive/{id}/pay/partial', [Web\Charge\ChargeReceiveController::class, 'payPartialStore'])->name('receive.pay.partial.store');

        Route::get('payment/{id}/pay', [Web\Charge\ChargePaymentController::class, 'payShow'])->name('payment.pay.show');
        Route::post('payment/{id}/pay/total', [Web\Charge\ChargePaymentController::class, 'payTotalStore'])->name('payment.pay.total.store');
        Route::post('payment/{id}/pay/partial', [Web\Charge\ChargePaymentController::class, 'payPartialStore'])->name('payment.pay.partial.store');
    });

    Route::as('bank.')->prefix('bank')->group(function () {
        Route::get('account/financial', [Web\AccountBankController::class, 'financial'])->name('account.financial');
        Route::resource('account', Web\AccountBankController::class);
        Route::prefix('account/{account}')->as('account.')->group(function () {
            Route::resource('transfer', Web\AccountBankTransferController::class)->only(['create', 'store']);
        });
    });

    Route::as('report.')->prefix('report')->group(function () {
        Route::get('month', [Web\ReportController::class, 'month'])->name('month');
        Route::get('/{report}', [Web\ReportController::class, 'index'])->name('index');
    });

    Route::resource('payment', Web\PaymentController::class)->only('index', 'destroy');
});
