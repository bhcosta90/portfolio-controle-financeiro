<?php

namespace Costa\Modules\Account\Entity;

use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\ValueObject\ModelObject;
use Costa\Shared\ValueObject\UuidObject;

class AccountEntity extends EntityAbstract
{
    public function __construct(
        protected ModelObject $entity,
        protected float $value,
        protected ?UuidObject $id = null,
    ) {
        parent::__construct();
    }

    public function update(
        string $entity,
        float $value,
    ) {
        $this->value = $value;
        $this->entity = $entity;
    }
}
