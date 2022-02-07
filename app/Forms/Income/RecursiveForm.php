<?php

namespace App\Forms\Income;

use App\Forms\Traits\ChargeFormTrait;
use Kris\LaravelFormBuilder\Form;

class RecursiveForm extends Form
{
    use ChargeFormTrait;

    public function buildForm()
    {
        $this->fieldCustomerName();
        $this->fieldResume();
        $this->fieldValue();
        $this->fieldDueDate("Start date");

        $this->add('type', 'select', [
            'label' => 'Forma da Cobrança',
            'choices' => [
                'Fixos' => [
                    'fifth_business_day' => 'Quinto dia útil',
                    'every_20th' => 'Dia 20',
                    'every_last_day' => 'Último dia do mês',
                ],
                'Período' => [
                    // 'anual' => 'Cobrança Anual',
                    'month' => 'Cobrança Mensal',
                    'twoweek' => 'Cobrança Quinzenal',
                    'week' => 'Cobrança Semanal',
                ]
            ],
            'rules' => 'required',
            'empty_value' => 'Selecione...'
        ]);


        $this->fieldDescription();
    }
}
