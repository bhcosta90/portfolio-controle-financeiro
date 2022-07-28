<?php

namespace App\Forms\Payment;

use Core\Application\BankAccount\Modules\Bank\Repository\BankRepository;
use Kris\LaravelFormBuilder\Form;

class TotalForm extends Form
{
    public function __construct(
        private BankRepository $bank,
    )
    {
        //
    }

    public function buildForm()
    {
        $this->add('bank_id', 'select', [
            'label' => __("Conta Bancária"),
            'choices' => $data = ['-1' => 'Movimentação de Caixa'] + $this->bank->pluck(),
            'rules' => ['required', 'in:' . implode(',', array_keys($data))],
            'empty_value' => __('Selecione')
        ]);

        $this->add('date_scheduled', 'date', [
            'label' => __("Agendar este pagamento"),
            'rules' => ['required'],
            'value' => date('Y-m-d'),
        ]);

        $this->add('type', 'hidden', ['value' => 'total']);
    }
}
