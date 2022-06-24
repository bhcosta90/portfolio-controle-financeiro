<?php

namespace Core\Financial\Account\Domain;

use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;

class AccountEntity extends EntityAbstract
{
    private function __construct(
        protected EntityAbstract $entity,
        protected float $value,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }

    public static function create(
        EntityAbstract $entity,
        float $value = 0,
        ?string $id = null,
        ?string $createdAt = null,
    ): self {
        return new self(
            entity: $entity,
            value: $value,
            id: $id ? new UuidObject($id) : null,
            createdAt: $createdAt ? new DateTime($createdAt) : null,
        );
    }
}
