<?php

namespace Costa\Modules\Bank\Entity;

use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\UuidObject;
use DateTime;

class BankEntity extends EntityAbstract
{
    public function __construct(
        protected InputNameObject $name,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,

    ) {
        parent::__construct();
    }

    public function update(
        InputNameObject $name,
    ) {
        $this->name = $name;
    }
}
