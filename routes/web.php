<?php

use App\Http\Controllers\Web\Admin;
use App\Http\Controllers\Web\Admin\TransactionController;
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

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::redirect('/', 'login');

Route::middleware('auth')->prefix('admin')->as('admin.')->group(function () {
    Route::as('relationship.')->prefix('relationship')->group(function () {
        Route::resource('customer', Admin\Relationship\CustomerController::class);
        Route::resource('company', Admin\Relationship\CompanyController::class);
    });

    Route::as('charge.')->prefix('charge')->group(function () {
        Route::resource('recurrence', Admin\Charge\RecurrenceController::class);
        Route::as('payment.')->prefix('payment')->group(function () {
            Route::resource('charge', Admin\Charge\Payment\ChargeController::class);
            Route::resource('pay', Admin\Charge\Payment\PayController::class)->only('show', 'update');
        });
        Route::as('receive.')->prefix('receive')->group(function () {
            Route::resource('charge', Admin\Charge\Receive\ChargeController::class);
            Route::resource('pay', Admin\Charge\Receive\PayController::class)->only('show', 'update');
        });
    });

    Route::get('/transaction', [TransactionController::class, 'index'])->name('transaction.index');
    Route::delete('/transaction/{uuid}', [TransactionController::class, 'destroy'])->name('transaction.destroy');
    Route::as('account.')->prefix('account')->group(function () {
        Route::resource('bank', Admin\Account\BankController::class);
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
