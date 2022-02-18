<?php

namespace Modules\Cobranca\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Cobranca\Models\ContaBancaria;
use Modules\Entidade\Services\BancoService;

class ContaBancariaForm extends Form
{
    public function buildForm()
    {
        $dataBanco = $this->getBancoService()->pluck();

        $this->add('entidade_id', 'select', [
            'rules' => ['required', 'in:' . implode(',', array_keys($dataBanco))],
            'label' => 'Banco',
            'choices' => $dataBanco,
            'empty_value' => 'Selecione'
        ]);

        $this->add('agencia', 'text', [
            'label' => 'Agência',
            'rules' => ['required'],
        ]);

        $this->add('conta', 'text', [
            'label' => 'Conta',
            'rules' => ['required'],
        ]);

        $dataBanco = ContaBancaria::getTipoAttribute();

        $this->add('tipo', 'select', [
            'rules' => ['in:' . implode(',', array_keys($dataBanco))],
            'label' => 'Tipo da Conta',
            'choices' => $dataBanco,
        ]);

        $dataBanco = ContaBancaria::getTipoDocumentoAttribute();

        $this->add('tipo_documento', 'select', [
            'rules' => ['in:' . implode(',', array_keys($dataBanco))],
            'label' => 'Tipo do Documento',
            'choices' => $dataBanco,
            'attr' => [
                'data-pf' => 'CPF',
                'data-pj' => "CNPJ",
                'data-ex' => 'Passaporte'
            ]
        ]);

        $this->add('documento', 'text', [
            'rules' => ['required', 'min:9', 'max:30'],
            'label' => 'Documento',
        ]);

        if (empty($this->request->route('contabancarium'))) {
            $this->add('valor', 'text', [
                'rules' => ['nullable'],
                'label' => 'Valor inicial',
                'attr' => [
                    'class' => 'form-control value'
                ]
            ]);
        }

        $this->add('ativo', 'select', [
            'label' => 'Ativo',
            'choices' => [
                1 => 'Sim',
                0 => 'Não'
            ],
        ]);
    }

    /**
     * @return BancoService
     */
    protected function getBancoService()
    {
        return app(BancoService::class);
    }
}
