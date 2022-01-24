<?php

namespace App\Forms\Income;

use App\Forms\Traits\ChargeForm;
use Kris\LaravelFormBuilder\Form;

class SimpleForm extends Form
{
    use ChargeForm;

    public function buildForm()
    {
        $this->fieldResume();
        $this->fieldValue();
        $this->fieldDescription();
    }
}
