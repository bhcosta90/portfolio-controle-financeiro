<?php

namespace App\Events;

use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Payment\Contracts\PaymentEventManagerContract;
use Costa\Modules\Payment\Events\PaymentEvent;

class PaymentEventManager implements PaymentEventManagerContract
{
    public function __construct(
        protected AccountRepositoryInterface $accountRepository
    )
    {
        
    }
    /** @param PaymentEvent $data */
    public function dispatch(object $data): void
    {
        $rs = $data->getPayload();

        if ($rs['completed'] ?? false) {
            $rs['account_from'] ? $this->accountRepository->decrementValue($rs['account_from'], $rs['value']) : null;
            $rs['account_to'] ? $this->accountRepository->incrementValue($rs['account_to'], $rs['value']) : null;
        }
    }
}
