<?php

namespace App\Events;

use Costa\Modules\Payment\Contracts\PaymentEventManagerContract;
use Costa\Modules\Payment\Events\PaymentEvent;

class PaymentEventManager implements PaymentEventManagerContract
{
    /** @param PaymentEvent $data */
    public function dispatch(object $data): void
    {
        event($data->getEventName(), $data->getPayload());
    }
}
