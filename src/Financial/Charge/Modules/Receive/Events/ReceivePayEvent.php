<?php

namespace Core\Financial\Charge\Modules\Receive\Events;

use Core\Financial\Charge\Modules\Receive\Domain\ReceiveEntity;
use Core\Shared\Abstracts\EventAbstract;

class ReceivePayEvent extends EventAbstract
{
    public function __construct(private ReceiveEntity $receiveEntity, private float $value)
    {
        //
    }

    public function name(): string
    {
        return 'charge.receive.pay.' . $this->receiveEntity->id();
    }

    public function payload(): array
    {
        return [
            'id' => $this->receiveEntity->id(),
            'value' => $this->value,
        ];
    }
}
