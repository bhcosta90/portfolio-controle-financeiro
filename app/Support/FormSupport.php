<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Kris\LaravelFormBuilder\FormBuilder;

class FormSupport
{
    private string $button = 'Save';

    public function __construct(
        private FormBuilder $formBuilder,
        private Request     $request
    )
    {
        //
    }

    public function button(string $title)
    {
        $this->button = $title;
        return $this;
    }

    public function run(
        string  $form,
        string  $action,
        ?object $model = null,
    )
    {
        $formRun = $this->formBuilder->create($form, [
            'method' => $model ? "PUT" : "POST",
            'url' => $action,
            'model' => $model,
        ]);

        $formRun->add('button-action', 'submit', [
            "attr" => [
                'class' => 'btn btn-primary btn-action',
                'data-label' => __($this->button),
                'value' => 'button-action'
            ],
            'label' => __($this->button),
        ]);

        return $formRun;
    }

    public function data(string $form)
    {
        $formRun = $this->formBuilder->create($form);

        if (!$formRun->isValid()) {
            throw ValidationException::withMessages($formRun->getErrors());
        }

        return $formRun->getFieldValues();
    }
}
