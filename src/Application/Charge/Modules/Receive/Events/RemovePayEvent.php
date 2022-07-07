<?php

namespace Core\Application\Charge\Modules\Receive\Events;

use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity;
use Core\Shared\Abstracts\EventAbstract;
use Core\Shared\ValueObjects\Input\FloatInputObject;

class RemovePayEvent extends EventAbstract
{
    public function __construct(
        private ReceiveEntity    $entity,
        private FloatInputObject $value,
    )
    {
        //
    }

    public function name(): string
    {
        return 'charge.receive.pay.remove.' . $this->entity->id();
    }

    public function payload(): array
    {
        return [
            'id' => $this->entity->id(),
            'value' => $this->value->value,
        ];
    }
}
