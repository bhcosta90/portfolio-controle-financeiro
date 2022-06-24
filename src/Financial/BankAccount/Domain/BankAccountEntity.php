<?php

namespace Core\Financial\BankAccount\Domain;

use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\Input\NameInputObject;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;

class BankAccountEntity extends EntityAbstract
{
    private function __construct(
        protected NameInputObject $name,
        protected float $value,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }

    public static function create(
        string $name,
        float $value,
        ?string $id = null,
        ?string $createdAt = null,
    ): self {
        return new self(
            name: new NameInputObject($name),
            value: $value,
            id: $id ? new UuidObject($id) : null,
            createdAt: $createdAt ? new DateTime($createdAt) : null,
        );
    }

    public function update(
        string $name,
    ): self {
        $this->name = new NameInputObject($name);
        return $this;
    }
}
