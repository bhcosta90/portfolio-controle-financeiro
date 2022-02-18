<?php

namespace Modules\Cobranca\Forms;

use Kris\LaravelFormBuilder\Form;

class FormaPagamentoForm extends Form
{
    public function buildForm()
    {
        $this->add('nome', 'text', [
            'label' => 'Nome',
            'rules' => ['required'],
        ]);

        $this->add('ordem', 'number', [
            'label' => 'Ordem',
            'rules' => ['required', 'numeric'],
        ]);

        $this->add('ativo', 'select', [
            'label' => 'Ativo',
            'choices' => [
                1 => 'Sim',
                0 => 'Não'
            ],
        ]);
    }
}
