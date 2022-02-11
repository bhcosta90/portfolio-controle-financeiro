<?php

use App\Http\Controllers\ChargeController;
use App\Http\Controllers\CostController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\RecurrencyController;
use App\Http\Controllers\UserProfileController;
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

Route::group(['middleware' => 'auth'], function(){
    Route::resource('income', IncomeController::class)->only(['index', 'create', 'store']);
    Route::resource('cost', CostController::class)->only(['index', 'create', 'store']);
    Route::resource('recurrency', RecurrencyController::class);
    Route::resource('charge', ChargeController::class)->except(['index', 'create', 'store']);
    Route::get('charge/{id}/pay', [ChargeController::class, 'pay'])->name('charge.pay.create');
    Route::put('charge/{id}/pay', [ChargeController::class, 'payUpdate'])->name('charge.pay.update');
    Route::get('user/profile', [UserProfileController::class, 'profile'])->name('user.profile.edit');
    Route::post('user/profile', [UserProfileController::class, 'saveProfile'])->name('user.profile.update');
});
