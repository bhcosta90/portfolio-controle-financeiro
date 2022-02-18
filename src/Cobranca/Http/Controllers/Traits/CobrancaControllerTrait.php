<?php

namespace Modules\Cobranca\Http\Controllers\Traits;

use Carbon\Carbon;
use Costa\LaravelPackage\Traits\Support\TableTrait;
use Costa\LaravelPackage\Traits\Web\WebCreateTrait;
use Costa\LaravelPackage\Traits\Web\WebIndexTrait;
use Illuminate\Http\Request;
use Modules\Cobranca\Models\Cobranca;

trait CobrancaControllerTrait
{
    use WebIndexTrait, WebCreateTrait, TableTrait;

    protected function estaVencido($rs, $string)
    {
        if ($rs->status == Cobranca::$STATUS_PAGO) {
            $ret = "<span class='text-success'>{$string}</span>";
            return $ret;
        }

        $dataVencimento = (new Carbon($rs->data_vencimento))->format('Y-m-d');
        $dataAtual = (new Carbon())->format('Y-m-d');

        if ($dataVencimento < $dataAtual) {
            $ret = "<span class='text-danger'>{$string}</span>";
            return $ret;
        }

        return $string;
    }

    protected function total(Request $request)
    {
        return [
            'data' => $this->getService()->total($request->except('_token')),
        ];
    }
}
