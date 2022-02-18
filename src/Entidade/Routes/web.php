<?php

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

use Illuminate\Support\Facades\Route;

tenantRoute(function () {
    Route::prefix('entidade')->as('entidade.')->middleware('auth')->group(function () {
        Route::get('/', 'EntidadeController@index');
        Route::resource('cliente', 'ClienteController')->except(['edit', 'update', 'destroy']);
        Route::resource('fornecedor', 'FornecedorController')->except(['edit', 'update', 'destroy']);
        Route::resource('banco', 'BancoController');

        Route::resource('entidade', 'EntidadeController')->only(['edit', 'update', 'destroy']);

        Route::group(['prefix' => '{entidade}'], function () {
            Route::resource('/contato', 'ContatoController');
        });
    });
});
