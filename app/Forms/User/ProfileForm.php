<?php

namespace App\Forms\User;

use Kris\LaravelFormBuilder\Form;

class ProfileForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'rules' => ['required', 'min:3', 'max:150'],
            'label' => __('Name'),
            'label_attr' => ['class' => 'labels']
        ]);

        $this->add('email', 'text', [
            'rules' => ['email', 'required'],
            'label' => __('Email'),
            'label_attr' => ['class' => 'labels'],
        ]);

        $this->add('password', 'password', [
            'rules' => ['required'],
            'label' => __('Confirmar senha'),
            'label_attr' => ['class' => 'labels'],
        ]);

        $this->add('balance_value', 'number', [
            'rules' => ['required', 'numeric'],
            'label' => __('Quantidade de parcela'),
            'label' => __('Minha conta'),
            'label_attr' => [
                'class' => 'labels',
                'data-default' => __('Valor da cobrança'),
                'data-recurrency' => __('Valor da cobrança'),
                'data-parcel' => __('Valor total da cobrança')
            ]
        ]);
    }
}
