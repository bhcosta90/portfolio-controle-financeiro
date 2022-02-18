<?php

namespace Modules\Cobranca\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Cobranca\Models\ContaPagar;
use Modules\Cobranca\Models\ContaReceber;
use Modules\Cobranca\Models\Frequencia;
use Modules\Cobranca\Models\Pagamento;

final class PagamentoService
{
    public function __construct(private Pagamento $repository)
    {
        //
    }

    public function data($filter = [])
    {
        return $this->repository
            ->with(['conta_bancaria.banco.entidade', 'forma_pagamento', 'entidade', 'usuario'])
            ->where(fn($q) => !empty($filter['conta_bancaria_id']) ? $q->where('conta_bancaria_id', $filter['conta_bancaria_id']) : null)
            ->whereBetween('created_at', [$filter['data_inicio'] . ' 00:00:00', $filter['data_final'] . ' 23:59:59'])
            ->orderBy('id', 'desc');
    }

    public function store(string $objClass, $data)
    {
        return DB::transaction(function () use ($objClass, $data) {
            $objContaBancaria = $this->getContaBancariaService()->getById($data['conta_bancaria_id']);
            $data['saldo_anterior'] = $objContaBancaria->valor;

            switch ($objClass) {
                case ContaPagar::class;
                    $data['saldo_atual'] = $objContaBancaria->valor - $data['valor_total'];
                    $objContaBancaria->decrement('valor', $data['valor_total']);
                    break;
                case ContaReceber::class;
                    $data['saldo_atual'] = $objContaBancaria->valor + $data['valor_total'];
                    $objContaBancaria->increment('valor', $data['valor_total']);
                    break;
                default:
                    throw new Exception('Não configurado essa classe de objeto: ' . $objClass);
            }

            return $this->repository->create($data);
        });
    }

    /**
     * @return ContaBancariaService
     */
    protected function getContaBancariaService()
    {
        return app(ContaBancariaService::class);
    }
}
