<?php

namespace Core\Financial\Payment\Events;

use Core\Financial\Payment\Domain\PaymentEntity;
use Core\Shared\Abstracts\PublishAbstract;

class PayEvent extends PublishAbstract
{
    public function __construct(
        private PaymentEntity $entity,
    ) {
        //
    }

    public function name(): string
    {
        return 'payment.execute.' . $this->entity->id();
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
