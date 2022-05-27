<?php

namespace Costa\Modules\Payment\UseCases;

use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Account\Repository\BankRepositoryInterface;
use Costa\Modules\Payment\Repository\PaymentRepositoryInterface;
use Costa\Modules\Payment\Shareds\Enums\Type;
use Costa\Shareds\ValueObjects\ModelObject;

class PaymentUseCase
{
    public function __construct(
        private AccountRepositoryInterface $account,
        private PaymentRepositoryInterface $payment,
        private BankRepositoryInterface $bank,
    ) {
        //
    }

    public function exec(DTO\Payment\Input $input)
    {
        $objAccount = $this->account->find($input->account);
        $objBank = $input->bank ? $this->bank->find($input->bank) : null;
        $objBankAccount = $objBank ? $this->account->find(new ModelObject($objBank->id, $objBank)) : null;
        
        if ($input->type == Type::CREDIT) {
            $this->account->addValue($objAccount, $input->value->value);
            
            foreach ($input->accounts as $account) {
                $objAccountArray = $this->account->find($account);
                $this->account->subValue($objAccountArray, $input->value->value);
            }
            
            if ($objBankAccount) {
                $this->account->subValue($objBankAccount, $input->value->value);
            }
        } else {
            $this->account->subValue($objAccount, $input->value->value);
            
            foreach ($input->accounts as $account) {
                $objAccountArray = $this->account->find($account);
                $this->account->addValue($objAccountArray, $input->value->value);
            }

            if ($objBankAccount) {
                $this->account->addValue($objBankAccount, $input->value->value);
            }
        }
    }
}
