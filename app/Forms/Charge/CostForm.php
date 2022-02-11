<?php

namespace App\Forms\Charge;

use Kris\LaravelFormBuilder\Form;

class CostForm extends Form
{
    use FormTrait;

    public function buildForm()
    {
        $this->name(__('Fornecedor'));
        $this->resume();
        $this->recurrency();
        $this->parcel();
        $this->dueDate();
        $this->value();
    }
}
