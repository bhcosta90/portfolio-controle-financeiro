<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class CustomerForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'rules' => ['min:3', 'max:150', 'required'],
            'label' => __('Nome')
        ]);
    }
}
