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

include __DIR__ . '/charge/cost.php';
include __DIR__ . '/charge/income.php';

Route::group(['prefix' => 'test'], function () {
    Route::get('ofx', function () {
        $ret = [];

        $ofx = new \Asmpkg\Ofx\Ofx(storage_path('ofx/NU_99544913_01DEZ2021_31DEZ2021.ofx'));
        $data = [];

        foreach ($ofx->bankTranList as $rs) {
            $data[] = $rs;
        }

        $ret[] = [
            'data' => [
                'inicio' => $ofx->dtStar,
                'final' => $ofx->dtEnd,
            ],
            'banco' => $ofx->bankId,
            'conta' => $ofx->acctId,
            'nome' => $ofx->org,
            'itens' => $data,
        ];

        $ofx = new \Asmpkg\Ofx\Ofx(storage_path('ofx/NU_99544913_01JAN2022_23JAN2022.ofx'));
        $data = [];

        foreach ($ofx->bankTranList as $rs) {
            $data[] = $rs;
        }

        $ret[] = [
            'data' => [
                'inicio' => $ofx->dtStar,
                'final' => $ofx->dtEnd,
            ],
            'banco' => $ofx->bankId,
            'conta' => $ofx->acctId,
            'nome' => $ofx->org,
            'itens' => $data,
        ];

        return $ret;
    });
});
