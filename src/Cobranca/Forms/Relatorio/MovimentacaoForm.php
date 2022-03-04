<?php

namespace Modules\Cobranca\Forms\Relatorio;

use Kris\LaravelFormBuilder\Form;
use Modules\Cobranca\Models\Cobranca;
use Modules\Cobranca\Services\ContaBancariaService;
use Modules\Cobranca\Services\FormaPagamentoService;

class MovimentacaoForm extends Form
{
    public function buildForm()
    {
        $dataSelect = $this->getContaBancariaService()->pluck();
        $this->add('conta_bancaria_id', 'select', [
            'rules' => ['nullable', 'in:' . implode(',', array_keys($dataSelect))],
            'label' => 'Conta bancária',
            'choices' => $dataSelect,
            'empty_value' => 'Todos',
            'attr' => ['class' => 'form-control select2'],
        ]);

        $dataSelect = $this->getFormaPagamentoService()->pluck();
        $this->add('forma_pagamento_id', 'select', [
            'rules' => ['nullable', 'in:' . implode(',', array_keys($dataSelect))],
            'label' => 'Forma de pagamento',
            'choices' => $dataSelect,
            'empty_value' => 'Todos',
            'attr' => ['class' => 'form-control select2'],
        ]);

        $dataSelect = [
            '1' => 'Entrada',
            '2' => 'Saída'
        ];

        $this->add('tipo_movimentacao', 'select', [
            'rules' => ['nullable', 'in:' . implode(',', array_keys($dataSelect))],
            'label' => 'Entrada / Saída',
            'choices' => $dataSelect,
            'empty_value' => 'Todos',
            'attr' => ['class' => 'form-control select2'],
        ]);

        $dataSelect = Cobranca::getTipoFormatarAttribute();

        $this->add('tipo_cobranca', 'select', [
            'rules' => ['nullable', 'in:' . implode(',', array_keys($dataSelect))],
            'label' => 'Tipos (Despesas, Contas)',
            'choices' => $dataSelect,
            'empty_value' => 'Todos',
            'attr' => ['class' => 'form-control select2'],
        ]);

        $this->add('data_inicio', 'date', [
            'label' => "Data inicial",
            'rules' => ['required', 'date'],
        ]);

        $this->add('data_final', 'date', [
            'label' => "Data final",
            'rules' => ['required', 'date'],
        ]);
    }

    /**
     * @return ContaBancariaService
     */
    protected function getContaBancariaService()
    {
        return app(ContaBancariaService::class);
    }

    /**
     * @return FormaPagamentoService
     */
    protected function getFormaPagamentoService()
    {
        return app(FormaPagamentoService::class);
    }
}
