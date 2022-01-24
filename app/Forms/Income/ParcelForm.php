<?php

namespace App\Forms\Income;

use App\Forms\Traits\ChargeForm;
use Kris\LaravelFormBuilder\Form;

class ParcelForm extends Form
{
    use ChargeForm;

    public function buildForm()
    {
        $this->fieldCustomerName();
        $this->fieldResume();
        $this->fieldValue();
        $this->fieldDueDate('Date of first parcel');

        $this->add('parcel_total', 'text', [
            'label' => __('Total of parcel'),
            'rules' => 'numeric|max:360|min:1',
        ]);

        $this->fieldDescription();
    }
}
