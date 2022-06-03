<?php

namespace Costa\Modules\Payment\UseCases;

use Costa\Modules\Account\Repository\AccountRepositoryInterface;

class PaymentTransferUseCase
{
    public function __construct(
        protected AccountRepositoryInterface $account
    ) {
        //
    }

    public function handle(DTO\PaymentTransfer\Input $input): DTO\PaymentTransfer\Output
    {
        if ($input->accountFrom) {
            $this->account->decrementValue($input->accountFrom, $input->value);
        }

        if ($input->accountTo) {
            $this->account->incrementValue($input->accountTo, $input->value);
        }

        return new DTO\PaymentTransfer\Output(true);
    }
}
