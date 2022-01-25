<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ChargeForm extends Form
{
    use Traits\ChargeForm;

    public function buildForm()
    {
        $this->fieldCustomerName();
        $this->fieldResume();
        $this->fieldDueDate();
        $this->fieldValue();
        $this->fieldDescription();
    }
}
