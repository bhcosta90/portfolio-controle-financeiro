<?php

namespace Modules\Entidade\Forms;

use Kris\LaravelFormBuilder\Form;

class ContatoForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'rules' => ['required', 'min:3', 'max:150'],
            'label' => 'Nome',
            'wrapper' => ['class' => 'col-4']
        ]);

        $this->add('email', 'email', [
            'rules' => ['nullable'],
            'label' => 'E-mail',
            'wrapper' => ['class' => 'col-4']
        ]);

        $this->add('telefone', 'text', [
            'rules' => ['required', 'min:3', 'max:40'],
            'label' => 'Telefone',
            'attr' => [
                'class' => 'form-control telefone'
            ],
        ]);
    }
}
