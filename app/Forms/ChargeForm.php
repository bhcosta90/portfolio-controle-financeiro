<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ChargeForm extends Form
{
    use Traits\ChargeFormTrait;

    public function buildForm()
    {
        $this->fieldCustomerName();
        $this->fieldResume();
        $this->fieldDueDate();
        $this->fieldValue();
        $this->fieldDescription();
    }
}
