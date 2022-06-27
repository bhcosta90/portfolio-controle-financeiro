<?php

namespace Core\Financial\Charge\Modules\Payment\Events;

use Core\Financial\Charge\Modules\Payment\Domain\PaymentEntity;
use Core\Shared\Abstracts\EventAbstract;

class PaymentCancelEvent extends EventAbstract
{
    public function __construct(private PaymentEntity $paymentEntity, private float $value)
    {
        //
    }

    public function name(): string
    {
        return 'charge.payment.cancel.' . $this->paymentEntity->id();
    }

    public function payload(): array
    {
        return [
            'id' => $this->paymentEntity->id(),
            'value' => $this->value,
        ];
    }
}