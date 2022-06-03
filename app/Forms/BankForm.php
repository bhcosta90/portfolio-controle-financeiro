<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class BankForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'rules' => ['required', 'min:3', 'max:100'],
            'label' => "Nome da Conta"
        ]);

        $this->add('value', 'text', [
            'rules' => ['required', 'numeric'],
            'label' => $this->request->route('bank') ? "Valor atual" : "Valor inicial da conta"
        ]);
    }
}
