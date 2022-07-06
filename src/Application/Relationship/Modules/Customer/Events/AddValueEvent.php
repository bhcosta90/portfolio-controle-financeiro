<?php

namespace Core\Application\Relationship\Modules\Customer\Events; 

use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Shared\Abstracts\EventAbstract;
use Core\Shared\ValueObjects\Input\FloatInputObject;

class AddValueEvent extends EventAbstract
{
    public function __construct(
        private CustomerEntity $entity,
        private FloatInputObject $value,
    ) {
        //
    }

    public function name(): string
    {
        return 'customer.value.add.' . $this->entity->id();
    }

    public function payload(): array
    {
        return [
            'id' => $this->entity->id(),
            'value' => abs($this->value->value),
        ];
    }
}
