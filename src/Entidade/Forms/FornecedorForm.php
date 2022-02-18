<?php

namespace Modules\Entidade\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Entidade\Services\BancoService;

class FornecedorForm extends Form
{
    public function buildForm()
    {
        $this->add('nome', 'text', [
            'rules' => ['required', 'min:3', 'max:150'],
            'label' => 'Nome da empresa',
        ]);

        $this->add('email', 'email', [
            'rules' => ['nullable'],
            'label' => 'E-mail geral da empresa',
        ]);

        $this->add('telefone', 'text', [
            'rules' => ['nullable', 'min:3', 'max:40'],
            'label' => 'Telefone',
            'attr' => [
                'class' => 'form-control telefone'
            ],
        ]);

        $this->add('tipo', 'select', [
            'rules' => ['required'],
            'label' => 'Tipo do Documento',
            'choices' => ['PJ' => "Pessoa Jurídica", 'PF' => 'Pessoa Física', 'EX' => 'Estrangeiro'],
            'attr' => [
                'data-pf' => 'CPF',
                'data-pj' => "CNPJ",
                'data-ex' => 'Passaporte'
            ]
        ]);

        $this->add('documento', 'text', [
            'rules' => ['nullable', 'min:9', 'max:30'],
            'label' => 'Documento',
        ]);

        $this->add('endereco', 'text', [
            'rules' => ['nullable'],
            'label' => 'Endereço',
        ]);

        $this->add('observacao', 'textarea', [
            'rules' => ['nullable'],
            'label' => 'Observação',
            'attr' => ['rows' => 4]
        ]);

        $this->add('ativo', 'select', [
            'label' => 'Ativo',
            'choices' => [
                1 => 'Sim',
                0 => 'Não'
            ],
        ]);

        $dataBanco = $this->getBancoService()->pluck();

        $this->add('banco_id', 'select', [
            'rules' => ['nullable', 'in:' . implode(',', array_keys($dataBanco))],
            'label' => 'Banco',
            'choices' => $dataBanco,
            'empty_value' => 'Selecione',
            'attr' => ['class' => 'select2']
        ]);

        $this->add('banco_agencia', 'text', [
            'rules' => ['nullable'],
            'label' => 'Agência',
        ]);

        $this->add('banco_conta', 'text', [
            'rules' => ['nullable'],
            'label' => 'Conta',
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
