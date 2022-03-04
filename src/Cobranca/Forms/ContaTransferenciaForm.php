<?php

namespace Modules\Cobranca\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Cobranca\Services\ContaBancariaService;

class ContaTransferenciaForm extends Form
{
    public function buildForm()
    {
        $this->add('conta_origem', 'select', [
            'attr' => ['class' => 'select2 form-control'],
            'label' => 'Conta Bancária - Origem',
            'choices' => $dataContaBancaria = $this->getContaBancariaService()->pluck(),
            'empty_value' => 'Selecione...',
            'rules' => ['required', 'in:' . implode(',', array_keys($dataContaBancaria))],
        ]);


        $this->add('conta_destino', 'select', [
            'attr' => ['class' => 'select2 form-control'],
            'label' => 'Conta Bancária - Destino',
            'choices' => $dataContaBancaria = $this->getContaBancariaService()->pluck(),
            'empty_value' => 'Selecione...',
            'rules' => ['required', 'in:' . implode(',', array_keys($dataContaBancaria))],
        ]);

        $this->add('valor_transferencia', 'text', [
            'label' => 'Valor da transferência',
            'attr' => [
                'class' => 'form-control value positive'
            ],
            'rules' => 'required'
        ]);

    }

    /**
     * @return ContaBancariaService
     */
    private function getContaBancariaService()
    {
        return app(ContaBancariaService::class);
    }
}
