<?php

use App\Http\Controllers\Admin\Web;
use App\Http\Controllers\User\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('profile', [ProfileController::class, 'index']);
Route::post('profile/data', [ProfileController::class, 'profile'])->name('profile.data');

Route::middleware('auth')->prefix('admin')->as('admin.')->group(function () {
    Route::prefix('relationship')->as('relationship.')->group(function () {
        Route::resource('company', Web\Relationship\CompanyController::class);
        Route::resource('customer', Web\Relationship\CustomerController::class);
    });
    Route::resource('bank', Web\BankAccountController::class);
    Route::resource('recurrence', Web\RecurrenceController::class);

    Route::prefix('charge')->as('charge.')->group(function () {
        Route::resource('receive', Web\Charge\ReceiveController::class);
        Route::resource('payment', Web\Charge\PaymentController::class);
    });
});
