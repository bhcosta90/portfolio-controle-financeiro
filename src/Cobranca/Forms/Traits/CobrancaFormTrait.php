<?php

namespace Modules\Cobranca\Forms\Traits;

use Carbon\Carbon;
use Modules\Cobranca\Forms\CobrancaParcelaForm;
use Modules\Cobranca\Services\ContaBancariaService;
use Modules\Cobranca\Services\FormaPagamentoService;
use Modules\Cobranca\Services\FrequenciaService;

trait CobrancaFormTrait {

    public function buildForm()
    {
        switch($this->request->segment(4)){
            case 'receber':
                $title = 'Cliente';
                $action = 'api.entidade.cliente.search';
                break;
            case 'pagar':
                $title = 'Fornecedor';
                $action = 'api.entidade.fornecedor.search';
                break;
        }

        $this->add('fornecedor', 'text', [
            'attr' => [
                'data-route' => route($action ?? 'api.entidade.cliente.search', ['tenant' => tenant()]),
                'data-ref' => $id = str()->uuid(),
                'class' => 'select2 form-control',
                'data-empty' => 'Selecione...'
            ],
            'label' => $title ?? 'Cliente'
        ]);

        $this->add('descricao', 'text', [
            'label' => 'Descrição',
            'rules' => 'nullable|min:3|max:100'
        ]);

        $this->add('data_emissao', 'date', [
            'label' => 'Data de emissão',
            'value' => $this->getData()['model']['data_emissao'] ?? (new Carbon())->format('Y-m-d'),
            'rules' => 'required|date'
        ]);

        $this->add('data_vencimento', 'date', [
            'label' => 'Data de vencimento',
            'value' => $this->getData()['model']['data_vencimento'] ?? (new Carbon())->format('Y-m-d'),
            'rules' => 'required|date'
        ]);

        $this->add('observacao', 'textarea', [
            'label' => 'Observação',
            'attr' => [
                'rows' => 4
            ]
        ]);

        $this->add('valor_cobranca', 'text', [
            'label' => 'Valor da cobrança',
            'attr' => [
                'class' => 'form-control value positive'
            ],
            'rules' => 'required'
        ]);

        $this->add('entidade_id', 'hidden', [
            'attr' => [
                'id' => $id
            ],
        ]);

        $this->add('frequencia_id', 'select', [
            'attr' => ['class' => 'select2 form-control'],
            'label' => 'Frequência da Cobrança',
            'choices' => $dataFrequencia = $this->getFrequenciaService()->pluck(),
            'empty_value' => 'Somente uma vez',
            'rules' => ['nullable', 'in:' . implode(',', array_keys($dataFrequencia))],
        ]);

        $this->add('forma_pagamento_id', 'select', [
            'attr' => ['class' => 'select2 form-control'],
            'label' => 'Forma de pagamento',
            'choices' => $dataFormaPagamento = $this->getFormaPagamentoService()->pluck(),
            'rules' => ['nullable', 'in:' . implode(',', array_keys($dataFormaPagamento))],
        ]);

        $this->add('conta_bancaria_id', 'select', [
            'attr' => ['class' => 'select2 form-control'],
            'label' => 'Conta Bancária',
            'choices' => $dataContaBancaria = $this->getContaBancariaService()->pluck(),
            'empty_value' => 'Selecione...',
            'rules' => ['nullable', 'in:' . implode(',', array_keys($dataContaBancaria))],
        ]);

        $this->add('frequencia_parcela', 'select', [
            'attr' => ['class' => 'select2 form-control'],
            'label' => 'Frequência da Parcela',
            'choices' => $dataFrequenciaParcela = $this->getFrequenciaService()->pluck('ordem_parcela'),
            'rules' => ['nullable', 'in:' . implode(',', array_keys($dataFrequenciaParcela))],
        ]);

        $this->add('total_parcela', 'number', [
            'label' => 'Total de parcela',
            'attr' => [
                'class' => 'form-control value positive'
            ],
            'rules' => 'required|min:1',
            'value' => 1
        ]);

        $this->add('parcelas', 'collection', [
            'type'    => 'form',
            'options' => [
                'class' => CobrancaParcelaForm::class,
                'label' => false,
                'wrapper' => ['class' => 'row'],
                'prefer_input' => false,
            ],
            'label' => false,
            'prefer_input' => true,
        ]);
    }

    /**
     * @return FrequenciaService
     */
    private function getFrequenciaService()
    {
        return app(FrequenciaService::class);
    }

    /**
     * @return ContaBancariaService
     */
    private function getContaBancariaService()
    {
        return app(ContaBancariaService::class);
    }

    /**
     * @return FormaPagamentoService
     */
    private function getFormaPagamentoService()
    {
        return app(FormaPagamentoService::class);
    }
}
