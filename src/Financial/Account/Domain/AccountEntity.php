<?php

namespace Core\Financial\Account\Domain;

use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;

class AccountEntity extends EntityAbstract 
{
    private function __construct(
        protected string $entity_type,
        protected string $entity_id,
        protected float $value,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }

    public static function create(
        string $entity_type,
        string $entity_id,
        float $value = 0,
        ?string $id = null,
        ?string $createdAt = null,
    ): self {
        return new self(
            entity_id: $entity_id,
            entity_type: $entity_type,
            value: $value,
            id: $id ? new UuidObject($id) : null,
            createdAt: $createdAt ? new DateTime($createdAt) : null,
        );
    }
}
