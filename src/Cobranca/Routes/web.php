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

// Route::prefix('cobranca')->group(function() {
//     Route::get('/', 'CobrancaController@index');
// });

use Illuminate\Support\Facades\Route;

tenantRoute(function () {
    Route::prefix('cobranca')->as('cobranca.')->middleware('auth')->group(function () {
        // Route::get('/movimentacao', 'ContaBancariaController@movimentacao')->name('contabancaria.movimentacao');
        Route::group(['prefix' => 'contabancaria', 'as' => 'contabancaria.'], function () {
            Route::resource('movimentacao', 'ContaBancariaMovimentacaoController')->only(['index', 'show']);
        });

        Route::group(['prefix' => 'relatorio', 'as' => 'relatorio.', 'namespace' => "Relatorio"], function () {
            Route::get('movimentacao', 'MovimentacaoController@index')->name('movimentacao.index');
            Route::GET('movimentacao/filter', 'MovimentacaoController@filter')->name('movimentacao.filter');
        });

        Route::resource('contabancaria', 'ContaBancariaController');

        Route::resource('formapagamento', 'FormaPagamentoController');
        Route::resource('frequencia', 'FrequenciaController');

        Route::get('cobranca/{cobranca}/pagar', 'CobrancaController@pagarShow')->name('cobranca.pagar.show');
        Route::post('cobranca/{cobranca}/pagar', 'CobrancaController@pagarStore')->name('cobranca.pagar.store');
        Route::resource('cobranca', 'CobrancaController');

        Route::group(['prefix' => 'conta', 'as' => 'conta.'], function(){

            Route::get('receber/total', 'ContaReceberController@total')->name('receber.total');
            Route::resource('receber', 'ContaReceberController');

            Route::get('pagar/total', 'ContaPagarController@total')->name('pagar.total');
            Route::resource('pagar', 'ContaPagarController');
        });
    });
});
