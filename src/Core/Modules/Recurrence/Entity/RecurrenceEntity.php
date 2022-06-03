<?php

namespace Costa\Modules\Recurrence\Entity;

use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\Input\InputIntObject;
use Costa\Shared\ValueObject\UuidObject;

class RecurrenceEntity extends EntityAbstract
{
    public function __construct(
        protected InputNameObject $name,
        protected InputIntObject $days,
        protected ?UuidObject $id = null,
    ) {
        parent::__construct();
    }

    public function update(
        InputNameObject $name,
        InputIntObject $days,
    ) {
        $this->days = $days;
        $this->name = $name;
    }
}
