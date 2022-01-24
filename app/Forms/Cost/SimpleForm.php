<?php

namespace App\Forms\Cost;

use App\Forms\Traits\ChargeForm;
use Kris\LaravelFormBuilder\Form;

class SimpleForm extends Form
{
    use ChargeForm;

    public function buildForm()
    {
        $this->fieldValue();
        $this->fieldResume();
        $this->add('parcel', 'text', [
            'label' => __('Parcel'),
            'rules' => ['numeric|max:360|min:1'],
        ]);
        $this->fieldDescription();
    }
}
