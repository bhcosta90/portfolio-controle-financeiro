<?php

namespace Modules\Cobranca\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Cobranca\Models\Cobranca;
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

        $tipoMovimentacao = match($filter['tipo_movimentacao'] ?? null) {
            '1' => Pagamento::$PAGAMENTO_TIPO_RECEITA,
            '2' => Pagamento::$PAGAMENTO_TIPO_DESPESA,
            default => null
        };

        return $this->repository
            ->with(['conta_bancaria.entidade', 'forma_pagamento', 'entidade', 'usuario'])
            ->whereBetween('pagamentos.created_at', [$filter['data_inicio'] . ' 00:00:00', $filter['data_final'] . ' 23:59:59'])
            ->where(empty($filter['conta_bancaria_id']) ? null : fn($q) => $q->whereHas('conta_bancaria', fn($q) => $q->where('uuid', $filter['conta_bancaria_id'])))
            ->where(empty($filter['forma_pagamento_id']) ? null : fn($q) => $q->whereHas('forma_pagamento', fn($q) => $q->where('uuid', $filter['forma_pagamento_id'])))
            ->where(empty($filter['tipo_cobranca']) ? null : fn($q) => $q->where('tipo_cobranca', $filter['tipo_cobranca']))
            ->where(empty($filter['tipo_movimento']) ? null : fn($q) => $q->where('tipo_movimento', $filter['tipo_movimento']))
            ->where(is_null($tipoMovimentacao) ? null : fn($q) => $q->whereIn('pagamento_type', $tipoMovimentacao))
            ->orderBy($filter['order'] ?? 'pagamentos.id', 'desc');
    }

    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            if ($data['conta_bancaria_id'] != Pagamento::$TIPO_CAIXA_MOVIMENTO) {
                $objContaBancaria = $this->getContaBancariaService()->getById($data['conta_bancaria_id']);
                $data['saldo_anterior'] = $objContaBancaria->valor;
                $data['saldo_atual'] = $objContaBancaria->valor + $data['valor_total'];

                switch ($data['tipo_cobranca']) {
                    case Cobranca::$TIPO_DEBITO;
                        $objContaBancaria->decrement('valor', abs($data['valor_total']));
                        break;
                    case Cobranca::$TIPO_CREDITO;
                        $objContaBancaria->increment('valor', abs($data['valor_total']));
                        break;
                    default:
                        throw new Exception('Não configurado esse tipo: ' . $data['tipo']);
                }
            } else {
                $data['tipo_movimento'] = $data['conta_bancaria_id'];

                $objTipoMovimento = $this->repository
                    ->select('saldo_atual')
                    ->where('tipo_movimento', $data['tipo_movimento'])
                    ->orderBy('id', 'desc')
                    ->limit(1)
                    ->first();

                $data['conta_bancaria_id']  = null;

                $data['saldo_anterior'] = $objTipoMovimento?->saldo_atual ?: 0;
                $data['saldo_atual'] = ($objTipoMovimento?->saldo_atual ?: 0) + $data['valor_total'];
            }

            return $this->repository->create($data + [
                'saldo_anterior' => 0,
                'saldo_atual' => 0,
            ]);
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
