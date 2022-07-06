<?php

namespace App\Forms;

use Core\Application\AccountBank\Repository\AccountBankRepository;
use Kris\LaravelFormBuilder\Form;

class PaymentForm extends Form
{
    public function __construct(
        private AccountBankRepository $bank,
    ) {
        //
    }

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
            'choices' => $data = ['-1' => 'Movimentação de Caixa'] + $this->bank->pluck(),
            'rules' => ['required', 'in:' . implode(',', array_keys($data))],
            'empty_value' => __('Selecione')
        ]);
    }
}
