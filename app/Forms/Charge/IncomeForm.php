<?php

namespace App\Forms\Charge;

use Kris\LaravelFormBuilder\Form;

class IncomeForm extends Form
{
    use FormTrait;

    public function buildForm()
    {
        $this->name(__('Cliente'));
        $this->resume();
        $this->recurrency();
        $this->parcel();
        $this->dueDate();
        $this->value();
    }
}
