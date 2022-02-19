<?php

namespace Modules\Cobranca\Http\Controllers;

use Costa\LaravelPackage\Traits\Support\TableTrait;
use Costa\LaravelPackage\Traits\Web\WebIndexTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Cobranca\Services\CobrancaMovimentacaoService;
use Modules\Cobranca\Services\ContaBancariaService;

class ContaBancariaMovimentacaoController extends Controller
{
    use WebIndexTrait, TableTrait;

    public function show($id, Request $request) {
        $request->request->add(['conta_bancaria_id' => $id]);
        return $this->index($request);
    }

    protected function service(): string
    {
        return CobrancaMovimentacaoService::class;
    }

    protected function view(): string
    {
        return 'cobranca::contabancaria.movimentacao';
    }

    protected function getTableColumns(): array
    {
        return [
            'Data' => fn($obj) => str()->date($obj->dt_created_at),
            'Descrição' => fn($obj) => $obj->descricao,
            'Movimento' => function($obj){
                $str = "{$obj->movimento}";
                if($obj->entidade || $obj->parcela){
                    $str .= "<small>(";

                    if($obj->entidade){
                        $str .= "{$obj->entidade->nome} - ";
                    }
                    if($obj->parcela) {
                        $str .= "Parcela: {$obj->parcela} - ";
                    }

                    $str = substr($str, 0, -3);

                    $str .= ")</small>";
                }
                return $str;
            },
            'Documento' => fn ($obj) => $obj->forma_pagamento->nome,
            'Usuário' => fn ($obj) => $obj->usuario->name,
            'Valor' => fn ($obj) => str()->numberEnToBr($obj->valor_total),
            'Saldo' => fn ($obj) => str()->numberEnToBr($obj->saldo_atual),
        ];
    }

    protected function getActionIndex($filter): array
    {
        return [
            'bancos' => $this->getContaBancariaService()->data()->get(),
            'ativo' => $this->getService()->getBancoAtivo($filter)?->uuid
        ];
    }

    /**
     * @return ContaBancariaService
     */
    protected function getContaBancariaService()
    {
        return app(ContaBancariaService::class);
    }
}
