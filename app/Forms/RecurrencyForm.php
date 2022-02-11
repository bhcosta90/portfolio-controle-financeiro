<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class RecurrencyForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'rule' => 'required|min:3|max:10'
        ]);

        if (!empty($this->getModel()['can_updated']) || empty($this->request->route('recurrency')) || $this->request->isMethod('PUT')) {
            $this->add('days', 'number', [
                'rule' => 'required|min:0|max:360',
                'label' => __('Quantidade de dias para próxima cobrança')
            ]);
        }
    }
}
