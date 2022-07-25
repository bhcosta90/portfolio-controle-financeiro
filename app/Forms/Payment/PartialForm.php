<?php

namespace App\Forms\Payment;

use Core\Application\BankAccount\Modules\Bank\Repository\BankRepository;
use Core\Application\Charge\Modules\Recurrence\Domain\RecurrenceEntity;
use Kris\LaravelFormBuilder\Form;

class PartialForm extends Form
{
    public function __construct(
        private BankRepository $bank,
    )
    {
        //
    }

    public function buildForm()
    {
        $this->add('value_pay', 'number', [
            'label' => __("Valor Pago"),
            'rules' => ['required', 'numeric', 'min:0.01', 'max:9999999999'],
            'attr' => ['placeholder' => 'Valor do Pagamento', 'class' => 'form-control value']
        ]);

        $this->add('date_scheduled', 'date', [
            'label' => __("Agendar este pagamento"),
            'rules' => ['required'],
            'value' => date('Y-m-d'),
        ]);

        $date = RecurrenceEntity::create(str()->uuid(), 'teste', 30)->calculate();
        $this->add('date_next', 'date', [
            'label' => __("Data do próximo pagamento"),
            'rules' => ['required'],
            'value' => $date->format('Y-m-d'),
        ]);

        $this->add('bank_id', 'select', [
            'label' => __("Conta Bancária"),
            'choices' => $data = ['-1' => 'Movimentação de Caixa'] + $this->bank->pluck(),
            'rules' => ['required', 'in:' . implode(',', array_keys($data))],
            'empty_value' => __('Selecione')
        ]);
    }
}
