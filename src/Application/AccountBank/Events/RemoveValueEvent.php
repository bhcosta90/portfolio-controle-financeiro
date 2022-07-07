<?php

namespace Core\Application\AccountBank\Events;

use Core\Application\AccountBank\Domain\AccountBankEntity;
use Core\Shared\Abstracts\EventAbstract;
use Core\Shared\ValueObjects\Input\FloatInputObject;
use Core\Shared\ValueObjects\UuidObject;

class RemoveValueEvent extends EventAbstract
{
    public function __construct(
        private AccountBankEntity $entity,
        private FloatInputObject  $value,
        private UuidObject        $idPayment,
    )
    {
        //
    }

    public function name(): string
    {
        return 'bank.value.remove.' . $this->entity->id();
    }

    public function payload(): array
    {
        return [
            'id' => $this->entity->id(),
            'value' => abs($this->value->value),
            'payment' => $this->idPayment,
        ];
    }
}
