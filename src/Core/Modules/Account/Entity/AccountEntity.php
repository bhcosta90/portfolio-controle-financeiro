<?php

namespace Costa\Modules\Account\Entity;

use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\ValueObject\ModelObject;
use Costa\Shared\ValueObject\UuidObject;
use DateTime;

class AccountEntity extends EntityAbstract
{
    public function __construct(
        protected ModelObject $entity,
        protected float $value,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,

    ) {
        parent::__construct();
    }

    public function update(
        float $value,
    ) {
        $this->value = $value;
    }
}
