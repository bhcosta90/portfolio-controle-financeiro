<?php

namespace Costa\Modules\Account\Entities;

use Costa\Shareds\Abstracts\EntityAbstract;
use Costa\Shareds\ValueObjects\ModelObject;
use DateTime;

class AccountEntity extends EntityAbstract
{
    public function __construct(
        public ModelObject $model,
        public float $value,
        protected ?int $increment = null,
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
