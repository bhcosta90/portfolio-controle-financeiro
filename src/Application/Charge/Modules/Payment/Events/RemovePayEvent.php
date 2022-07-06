<?php

namespace Core\Application\Charge\Modules\Payment\Events;

use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity;
use Core\Shared\Abstracts\EventAbstract;
use Core\Shared\ValueObjects\Input\FloatInputObject;

class RemovePayEvent extends EventAbstract
{
    public function __construct(
        private PaymentEntity $entity,
        private FloatInputObject $value,
    ) {
        //
    }

    public function name(): string
    {
        return 'charge.payment.pay.remove.' . $this->entity->id();
    }

    public function payload(): array
    {
        return [
            'id' => $this->entity->id(),
            'value' => $this->value->value,
        ];
    }
}
