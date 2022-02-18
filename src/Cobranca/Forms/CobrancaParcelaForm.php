<?php

namespace Modules\Cobranca\Forms;

use Kris\LaravelFormBuilder\Form;

class CobrancaParcelaForm extends Form
{
    public function buildForm()
    {
        $this->add('valor', 'text', [
            'wrapper' => ['class' => 'col-md-6 mb-3'],
            'attr' => ['class' => 'form-control value positive']
        ]);
        $this->add('data', 'date', [
            'wrapper' => ['class' => 'col-md-6 mb-3'],
            'attr' => ['class' => 'form-control date'],
        ]);
    }
}
