<?php

use Illuminate\Http\Request;
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

tenantRoute(function () {
    Route::prefix('api/entidade')->as('api.entidade.')->group(function () {
        Route::get('cliente/search', 'ClienteController@search')->name('cliente.search');
        Route::get('fornecedor/search', 'FornecedorController@search')->name('fornecedor.search');
    });
}, 'api');
