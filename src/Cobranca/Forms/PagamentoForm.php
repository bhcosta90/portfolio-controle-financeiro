<?php

namespace Modules\Cobranca\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Cobranca\Services\ContaBancariaService;
use Modules\Cobranca\Services\FormaPagamentoService;

class PagamentoForm extends Form
{
    public function buildForm()
    {
        $this->add('valor_cobranca', 'text', [
            'label' => 'Valor da cobrança',
            'attr' => [
                'class' => 'form-control value positive'
            ],
            'rules' => 'required',
            'value' => '',
        ]);

        $this->add('valor_multa', 'text', [
            'label' => 'Multa em R$',
            'attr' => [
                'class' => 'form-control value positive'
            ],
            'rules' => 'nullable',
            'value' => ''
        ]);

        $this->add('valor_juros', 'text', [
            'label' => 'Júros em R$',
            'attr' => [
                'class' => 'form-control value positive'
            ],
            'rules' => 'nullable',
            'value' => ''
        ]);

        $this->add('valor_desconto', 'text', [
            'label' => 'Desconto em R$',
            'attr' => [
                'class' => 'form-control value positive'
            ],
            'rules' => 'nullable',
            'value' => ''
        ]);

        $valor = $this->getData()['model']->valor_cobranca;
        $valorJuros = $this->request->valor_juros ?: 0;
        $valorMulta = $this->request->valor_multa ?: 0;
        $valorDesconto = $this->request->valor_desconto ?: 0;

        $valor += $valorJuros;
        $valor += $valorMulta;
        $valor -= $valorDesconto;

        $this->add('valor_total', 'number', [
            'label' => 'Subtotal',
            'attr' => [
                'class' => 'form-control',
                'readonly' => true,
            ],
            'rules' => ['required', 'numeric', 'min:0', 'max:' . (str()->truncate($valor) + 0.01)],
            'value' => '',
        ]);

        $this->add('conta_bancaria_id', 'select', [
            'class' => 'select2 form-control',
            'label' => 'Conta Bancária',
            'choices' => $dataContaBancaria = $this->getContaBancariaService()->pluck(),
            'empty_value' => 'Selecione...',
            'rules' => ['required', 'in:' . implode(',', array_keys($dataContaBancaria))],
            'selected' => $this->getData()['model']?->conta_bancaria?->uuid
        ]);

        $this->add('forma_pagamento_id', 'select', [
            'class' => 'select2 form-control',
            'label' => 'Forma de pagamento',
            'choices' => $dataFormaPagamento = $this->getFormaPagamentoService()->pluck(),
            'rules' => ['required', 'in:' . implode(',', array_keys($dataFormaPagamento))],
            'selected' => $this->getData()['model']?->forma_pagamento->uuid
        ]);
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
