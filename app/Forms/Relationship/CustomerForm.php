<?php

namespace App\Forms\Relationship;

use Kris\LaravelFormBuilder\Form;

class CustomerForm extends Form
{
    public function buildForm()
    {
        $this->add(
            name: 'name',
            options: [
                'label' => __('Name'),
                'rules' => 'required|max:100'
            ]
        );
    }
}
