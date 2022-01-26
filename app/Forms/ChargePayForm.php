<?php

namespace App\Forms;

use App\Models\Account;
use App\Services\AccountService;
use Kris\LaravelFormBuilder\Form;

class ChargePayForm extends Form
{
    public function buildForm()
    {
        $this->add('account_id', 'select', [
            'label' => __('Account'),
            'choices' => $ids = $this->getAccountService()->pluck(auth()->user()->id, [Account::TYPE_PAYMENT]),
            'empty_value' => __('Select'),
            'rules' => ['required', 'in:' . implode(',', array_keys($ids))]
        ]);

        $this->add('value', 'number', [
            'label' => __('Payment value'),
            'attrs' => ['step' => '0.01'],
            'rules' => ['required', 'numeric', 'min:0.01', 'max:9999999999']
        ]);
    }

    /**
     * @return AccountService
     */
    protected function getAccountService(){
        return app(AccountService::class);
    }
}
