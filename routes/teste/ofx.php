<?php

use Illuminate\Support\Facades\Route;

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
