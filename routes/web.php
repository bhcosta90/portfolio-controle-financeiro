<?php

use App\Http\Controllers\Web\{ChargeController, CostController, IncomeController};
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

Route::view('/', 'welcome');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function(){
    include __DIR__ . '/charge/cost.php';
    include __DIR__ . '/charge/income.php';

    Route::resource('charge', ChargeController::class)->except(['index']);
    Route::group(['prefix' => 'charge', 'as' => 'charge.'], function () {
        Route::get('{uuid}/pay', [ChargeController::class, 'pay'])->name('pay.create');
        Route::post('{uuid}/pay', [ChargeController::class, 'payStore'])->name('pay.store');
    });

    Route::group(['prefix' => 'test'], function () {
        include __DIR__ . '/teste/ofx.php';
    });
});
