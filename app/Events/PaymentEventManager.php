<?php

namespace App\Events;

use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Payment\Contracts\PaymentEventManagerContract;
use Costa\Modules\Payment\Entity\PaymentEntity;

class PaymentEventManager implements PaymentEventManagerContract
{
    public function __construct(
        protected AccountRepositoryInterface $accountRepository
    )
    {
        
    }
    /** @param PaymentEntity $data */
    public function dispatch(object $data): void
    {
        if ($data->completed) {
            $data->accountFrom ? $this->accountRepository->decrementValue($data->accountFrom, $data->value) : null;
            $data->accountTo ? $this->accountRepository->incrementValue($data->accountTo, $data->value) : null;
        }
    }
}
