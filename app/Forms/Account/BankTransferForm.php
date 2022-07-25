<?php

namespace App\Forms\Account;

use Core\Application\BankAccount\Modules\Bank\Repository\BankRepository;
use Kris\LaravelFormBuilder\Form;

class BankTransferForm extends Form
{
    public function __construct(
        private BankRepository $bank,
    )
    {
        //
    }

    public function buildForm()
    {
        
        $this->add(
            name: 'bank_account_id',
            type: 'select',
            options: [
                'choices' => $data = $this->bank->pluck($this->getData()),
                'label' => __('Conta bancária'),
                'rules' => 'required|in:' . implode(',', array_keys($data)),
                'empty_value' => __('Selecione') . '...',
            ]
        );

        $this->add(
            name: 'value',
            options: [
                'label' => __('Valor'),
                'rules' => 'required|numeric',
                'attr' => [
                    'step' => '0.01'
                ]
            ]
        );
    }
}
