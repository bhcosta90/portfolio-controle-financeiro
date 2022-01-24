<?php

namespace App\Forms\Traits;

trait ChargeForm
{
    public function fieldValue()
    {
        $this->add('value', 'number', [
            'label' => __('Value'),
            'attrs' => ['step' => '0.01'],
            'rules' => ['required', 'numeric', 'min:0.01', 'max:9999999999']
        ]);
    }

    public function fieldResume()
    {
        $this->add('resume', 'text', [
            'label' => __('Resume'),
            'rules' => ['required'],
        ]);
    }

    public function fieldDescription()
    {
        $this->add('description', 'textarea', [
            'label' => __('Description'),
            'rules' => ['nullable'],
        ]);
    }

    public function fieldDueDate()
    {
        $this->add('due_date', 'date', [
            'label' => __('Description'),
            'rules' => 'required|date_format:Y-m-d',
        ]);
    }

    public function fieldCustomerName()
    {
        $this->add('customer_name', 'text', [
            'label' => __('Name of customer'),
            'rules' => 'required|min:3|max:150',
        ]);
    }
}
