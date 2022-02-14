<?php

namespace App\Forms\Charge;

use Kris\LaravelFormBuilder\Form;

class ChargePayForm extends Form
{
    public function buildForm()
    {
        $this->add('value_pay', 'text', [
            'rules' => ['required'],
            'label' => __('Quantidade de parcela'),
            'label' => __('Valor do pagamento'),
            'label_attr' => [
                'data-default' => __('Valor da cobrança'),
                'data-recurrency' => __('Valor da cobrança'),
                'data-parcel' => __('Valor total da cobrança')
            ],
            'attr' => [
                'class' => 'form-control value'
            ]
        ]);
    }
}
