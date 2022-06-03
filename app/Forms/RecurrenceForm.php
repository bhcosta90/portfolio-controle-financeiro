<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class RecurrenceForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'rules' => ['required', 'min:3', 'max:100'],
            'label' => "Nome"
        ]);

        $this->add('days', 'number', [
            'rules' => ['required', 'min:1'],
            'label' => "Quantidade de dias da recorrência"
        ]);
    }
}
