<?php

namespace App\Forms\Charge;

use App\Models\Charge;
use App\Services\RecurrencyService;
use Illuminate\Validation\Rules\RequiredIf;

trait FormTrait
{
    protected function name($label)
    {
        $this->add('name', 'text', [
            'rules' => ['required', 'min:3', 'max:150'],
            'label' => $label,
            'attr' => [
                'class' => 'customer_name form-control'
            ]
        ]);
    }

    protected function resume()
    {
        $this->add('resume', 'text', [
            'rules' => ['required', 'min:3', 'max:150'],
            'label' => __('Resumo')
        ]);
    }

    protected function recurrency()
    {
        $this->add('recurrency', 'select', [
            'choices' => $data =  Charge::getTypeOptionsAttribute() + $this->getRecurrencyService()->pluck(),
            'label' => __("Frequência"),
            'rules' => ['required', 'in:' . implode(',', array_keys($data))],
        ]);
    }

    protected function dueDate()
    {
        $this->add('due_date', 'date', [
            'rules' => ['required', 'date'],
            'label' => __('Data de vencimento'),
            'label_attr' => [
                'data-default' => __('Data de vencimento'),
                'data-recurrency' => __('Data inicial'),
                'data-parcel' => __('Data da primeira parcela')
            ]
        ]);
    }

    protected function parcel()
    {
        $this->add('parcel', 'number', [
            'rules' => ['nullable', 'min:1', 'numeric', 'max:360', new RequiredIf(fn () => request('recurrency') == -2)],
            'label' => __('Quantidade de parcela'),
        ]);
    }

    protected function value()
    {
        $this->add('value', 'number', [
            'rules' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'label' => __('Quantidade de parcela'),
            'label' => __('Valor da cobrança'),
            'label_attr' => [
                'data-default' => __('Valor da cobrança'),
                'data-recurrency' => __('Valor da cobrança'),
                'data-parcel' => __('Valor total da cobrança')
            ]
        ]);
    }

    /**
     * @return RecurrencyService
     */
    protected function getRecurrencyService()
    {
        return app(RecurrencyService::class);
    }
}
