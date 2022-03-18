<?php

namespace Modules\Cobranca\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Cobranca\Models\Cobranca;
use Modules\Cobranca\Models\ContaReceber;
use Modules\Cobranca\Models\ContaTransferencia;

final class ContaTransferenciaService
{
    public function __construct(private ContaTransferencia $repository)
    {
        //
    }

    public function store($idUser, $idContaOrigem, $idContaDestino, $valor)
    {
        $obj = $this->model()->create();

        $data = [
            'data_vencimento' => Carbon::now()->format('Y-m-d'),
            'data_emissao' => Carbon::now()->format('Y-m-d'),
            'forma_pagamento_id' => $idFormaPagamento = $this->getFormaPagamentoService()->getByTipo('transferencia'),
            'valor_cobranca' => $valor,
            'status' => Cobranca::$STATUS_PAGO,
        ];

        $cobrancaOrigem = $this->getCobrancaService()->store($obj, $data + [
            'tipo' => Cobranca::$TIPO_DEBITO,
            'conta_bancaria_id' => $idContaOrigem
        ])[0];

        $cobrancaDestino = $this->getCobrancaService()->store($obj, $data + [
            'tipo' => Cobranca::$TIPO_CREDITO,
            'conta_bancaria_id' => $idContaDestino
        ])[0];


        $this->getPagamentoService()->store([
            'conta_bancaria_id' => $cobrancaOrigem->conta_bancaria_id,
            'valor_total' => $valor * -1,
            'tipo' => $cobrancaOrigem->tipo,
            'tipo_cobranca' => $cobrancaOrigem->tipo,
            'valor' => $valor,
            'valor_cobranca' => $valor,
            'valor_multa' => 0,
            'valor_juros' => 0,
            'valor_desconto' => 0,
            'user_id' => $idUser,
            'forma_pagamento_id' => $idFormaPagamento,
            'pagamento_type' => get_class($obj),
            'movimento' => 'Transfência entre conta bancária'
        ]);

        $this->getPagamentoService()->store([
            'conta_bancaria_id' => $cobrancaDestino->conta_bancaria_id,
            'valor_total' => $valor,
            'tipo' => $cobrancaDestino->tipo,
            'tipo_cobranca' => $cobrancaDestino->tipo,
            'valor' => $valor,
            'valor_cobranca' => $valor,
            'valor_multa' => 0,
            'valor_juros' => 0,
            'valor_desconto' => 0,
            'user_id' => $idUser,
            'forma_pagamento_id' => $idFormaPagamento,
            'pagamento_type' => get_class($obj),
            'movimento' => 'Transfência entre conta bancária'
        ]);


        return $obj;
    }

    protected function model(): Model
    {
        return app(ContaTransferencia::class);
    }

    /**
     * @return CobrancaService
     */
    protected function getCobrancaService()
    {
        return app(CobrancaService::class);
    }

    /**
     * @return FormaPagamentoService
     */
    protected function getFormaPagamentoService()
    {
        return app(FormaPagamentoService::class);
    }

    /**
     * @return PagamentoService
     */
    protected function getPagamentoService()
    {
        return app(PagamentoService::class);
    }
}
