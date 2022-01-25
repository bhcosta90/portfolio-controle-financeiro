<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ChargePayForm extends Form
{
    public function buildForm()
    {
        $this->add('value', 'number', [
            'label' => __('Payment value'),
            'attrs' => ['step' => '0.01'],
            'rules' => ['required', 'numeric', 'min:0.01', 'max:9999999999']
        ]);
    }
}
