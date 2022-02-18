<?php

namespace Modules\Entidade\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Entidade\Models\Banco;

class BancoForm extends Form
{
    public function buildForm()
    {
        $type = Banco::class;

        $id = $this->request->route('banco');
        $tenant = tenant('id');

        $this->add('nome', 'text', [
            'rules' => ['required', 'min:3', 'max:150', "unique:entidades,nome,{$id},uuid,entidade_type,{$type},tenant_id,{$tenant}"],
            'label' => 'Nome',
        ]);

        $this->add('email', 'email', [
            'rules' => ['nullable'],
            'label' => 'E-mail',
        ]);

        $this->add('telefone', 'text', [
            'rules' => ['nullable', 'min:3', 'max:40'],
            'label' => 'Telefone',
            'attr' => [
                'class' => 'form-control telefone'
            ],
        ]);

        $this->add('tipo', 'hidden', [
            'value' => 'PJ'
        ]);

        $this->add('documento', 'text', [
            'rules' => ['nullable', 'min:9', 'max:30'],
            'label' => 'CNPJ',
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

        $this->add('banco_codigo', 'select', [
            'choices' => $bancos = Banco::getBancoCodigoAttribute(),
            'rules' => ['nullable', 'in:' . implode(',', array_keys($bancos))],
            'label' => 'Banco',
            'empty_value' => 'Selecione...'
        ]);
    }
}
