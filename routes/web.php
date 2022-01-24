<?php

use App\Http\Controllers\Web\{CostController, IncomeController};
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

Route::get('cost/create/normal', [CostController::class, 'create'])->name('cost.create.normal');
Route::get('cost/create/parcel', [CostController::class, 'create'])->name('cost.create.parcel');
Route::get('cost/create/recursive', [CostController::class, 'create'])->name('cost.create.recursive');
Route::resource('cost', CostController::class)->only(['index']);

Route::get('income/create/normal', [IncomeController::class, 'create'])->name('income.create.normal');
Route::get('income/create/parcel', [IncomeController::class, 'create'])->name('income.create.parcel');
Route::get('income/create/recursive', [IncomeController::class, 'create'])->name('income.create.recursive');
Route::resource('income', IncomeController::class)->only(['index']);
