<?php

namespace Core\Financial\Payment\Events;

use Core\Financial\Payment\Domain\PaymentEntity;
use Core\Shared\Abstracts\PublishAbstract;

class PayEvent extends PublishAbstract
{
    const CACHE_PAY_EVENT = 'v2';

    public function __construct(
        private PaymentEntity $entity,
    ) {
        //
    }

    public function name(): string
    {
        return 'payment.execute.' . self::CACHE_PAY_EVENT . '.' . $this->entity->id();
    }

    public function publish(): array
    {
        return [
            'id' => $this->entity->id(),
            'value' => $this->entity->value,
            'account_from' => $this->entity->accountFrom?->id(),
            'account_to' => $this->entity->accountTo?->id(),
        ];
    }
}
