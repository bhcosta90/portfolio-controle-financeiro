<?php

namespace App\Forms\Cost;

use App\Forms\Traits\ChargeForm;
use Kris\LaravelFormBuilder\Form;

class SimpleForm extends Form
{
    use ChargeForm;

    public function buildForm()
    {
        $this->fieldCustomerName();
        $this->fieldResume();
        $this->fieldValue();
        $this->fieldDueDate();
        $this->fieldDescription();
    }
}
