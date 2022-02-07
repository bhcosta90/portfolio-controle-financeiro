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
        if (!empty($this->getModel()['type']) || $this->request->method() == 'PUT') {
            $this->add('update_value', 'checkbox', [
                'label' => __('Update value of future charges?')
            ]);
        }
        $this->fieldDescription();
    }
}
