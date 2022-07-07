<?php

namespace Core\Application\Relationship\Modules\Company\Events;

use Core\Application\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Shared\Abstracts\EventAbstract;
use Core\Shared\ValueObjects\Input\FloatInputObject;
use Core\Shared\ValueObjects\UuidObject;

class AddValueEvent extends EventAbstract
{
    public function __construct(
        private CompanyEntity    $entity,
        private FloatInputObject $value,
        private UuidObject       $idPayment,
    )
    {
        //
    }

    public function name(): string
    {
        return 'company.value.add.' . $this->entity->id();
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
