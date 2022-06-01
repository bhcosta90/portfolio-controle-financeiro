<?php

namespace Costa\Modules\Payment\Events;

use Costa\Modules\Payment\Entity\PaymentEntity;
use Costa\Shared\Contracts\EventInterface;

class PaymentEvent implements EventInterface
{
    public function __construct(protected PaymentEntity $payment)
    {
        //
    }

    public function getEventName(): string
    {
        return 'payment.pay.' . $this->payment->id();
    }

    public function getPayload(): array
    {
        return [
            'account_from' => $this->payment->accountFrom,
            'account_to' => $this->payment->accountTo,
            'value' => $this->payment->value,
            'completed' => $this->payment->completed
        ];
    }
}
