<?php

namespace App\Forms\Account;

use Kris\LaravelFormBuilder\Form;

class BankForm extends Form
{
    public function buildForm()
    {
        $this->add(
            name: 'name',
            options: [
                'label' => __('Name'),
                'rules' => 'required|max:100'
            ]
        );

        $this->add(
            name: 'value',
            options: [
                'label' => __('Valor'),
                'rules' => 'required|numeric',
                'attr' => [
                    'step' => '0.01'
                ]
            ]
        );

        $this->add(
            name: 'active',
            type: 'checkbox',
            options: [
                'label' => __('Ativo'),
                'rules' => 'nullable',
            ]
        );
    }
}
