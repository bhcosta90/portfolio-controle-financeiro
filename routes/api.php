<?php

use App\Http\Controllers\Api\{
    CostController,
    FormPaymentController,
    IncomeController
};
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

Route::resource('cost', CostController::class);
Route::resource('income', IncomeController::class);
Route::resource('payment', FormPaymentController::class)->only(['index', 'create', 'store']);
