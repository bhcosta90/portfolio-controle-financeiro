<?php

namespace App\Events\Payment;

use Costa\Modules\Payment\Contracts\PaymentEventManagerContract;

class PaymentEventManager implements PaymentEventManagerContract
{
    public function dispatch(object $data): void
    {
        dd($data);
    }
}