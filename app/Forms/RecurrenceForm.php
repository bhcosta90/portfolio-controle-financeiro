<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class RecurrenceForm extends Form
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
            name: 'days',
            options: [
                'label' => __('Quantidade de Dias'),
                'rules' => 'required|numeric'
            ]
        );
    }
}
