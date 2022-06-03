<?php

namespace App\Forms\Charge;

use Costa\Modules\Account\Repository\BankRepositoryInterface;
use Kris\LaravelFormBuilder\Form;

class ChargePayForm extends Form
{
    public function buildForm()
    {
        $this->add('value_charge', 'number', [
            'label' => __("Valor da Cobrança"),
            'rules' => ['nullable', 'numeric', 'min:0.01', 'max:9999999999'],
            'attr' => ['placeholder' => 'Valor da Cobrança', 'class' => 'form-control value']
        ]);

        $this->add('value_pay', 'number', [
            'label' => __("Valor Pago"),
            'rules' => ['required', 'numeric', 'min:0.01', 'max:9999999999'],
            'attr' => ['placeholder' => 'Valor do Pagamento', 'class' => 'form-control value']
        ]);

        $this->add('date_scheduled', 'date', [
            'label' => __("Agendar pagamento"),
            'rules' => ['required'],
            'value' => date('Y-m-d'),
        ]);

        $this->add('bank_id', 'select', [
            'label' => __("Conta Bancária"),
            'choices' => $data = $this->getBankRepositoryInterface()->pluck(),
            'rules' => ['nullable', 'in:' . implode(',', array_keys($data))],
            'empty_value' => __('Movimentação de caixa')
        ]);
    }

    private function getBankRepositoryInterface(): BankRepositoryInterface
    {
        return app(BankRepositoryInterface::class);
    }
}
