<?php

namespace App\Forms\User;

use Kris\LaravelFormBuilder\Form;

class SharedForm extends Form
{
    public function buildForm()
    {
        $this->add('email', 'text', [
            'rules' => ['email', 'required'],
            'label' => __('Email'),
            'label_attr' => ['class' => 'labels'],
        ]);
    }
}
