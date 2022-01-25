<?php

namespace App\Forms\Income;

use App\Forms\Traits\ChargeFormTrait;
use Kris\LaravelFormBuilder\Form;

class SimpleForm extends Form
{
    use ChargeFormTrait;

    public function buildForm()
    {
        $this->fieldCustomerName();
        $this->fieldResume();
        $this->fieldValue();
        $this->fieldDueDate();
        $this->fieldDescription();
    }
}
