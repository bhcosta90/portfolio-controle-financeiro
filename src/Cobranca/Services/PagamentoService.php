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
            ->whereBetween('created_at', [$filter['data_inicio'] . ' 00:00:00', $filter['data_final'] . ' 23:59:59'])
            ->where(empty($filter['conta_bancaria_id']) ? null : fn($q) => $q->whereHas('conta_bancaria', fn($q) => $q->where('uuid', $filter['conta_bancaria_id'])))
            ->where(empty($filter['forma_pagamento_id']) ? null : fn($q) => $q->whereHas('forma_pagamento', fn($q) => $q->where('uuid', $filter['forma_pagamento_id'])))
            ->where(empty($filter['tipo_cobranca']) ? null : fn($q) => $q->where('tipo', $filter['tipo_cobranca']))
            ->where(is_null($tipoMovimentacao) ? null : fn($q) => $q->whereIn('pagamento_type', $tipoMovimentacao))
            ->orderBy('id', 'desc');
    }

    public function store(string $objClass, $data)
    {
        return DB::transaction(function () use ($objClass, $data) {
            $objContaBancaria = $this->getContaBancariaService()->getById($data['conta_bancaria_id']);
            $data['saldo_anterior'] = $objContaBancaria->valor;
            $data['saldo_atual'] = $objContaBancaria->valor + $data['valor_total'];

            switch ($data['tipo']) {
                case Cobranca::$TIPO_DEBITO;
                    $objContaBancaria->decrement('valor', abs($data['valor_total']));
                    break;
                case Cobranca::$TIPO_CREDITO;
                    $objContaBancaria->increment('valor', abs($data['valor_total']));
                    break;
                default:
                    throw new Exception('Não configurado esse tipo: ' . $data['tipo']);
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
