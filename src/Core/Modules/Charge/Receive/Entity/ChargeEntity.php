<?php

namespace Costa\Modules\Charge\Receive\Entity;

use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\UuidObject;

class ChargeEntity extends EntityAbstract
{
    public function __construct(
        protected InputNameObject $name,
        protected ?UuidObject $id = null,
    ) {
        parent::__construct();
    }

    public function update(
        InputNameObject $name,
    ) {
        $this->name = $name;
    }
}
