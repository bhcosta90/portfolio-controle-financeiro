<?php

namespace Costa\Modules\Charge\Entities;

use Costa\Shareds\Abstracts\EntityAbstract;
use Costa\Shareds\Validations\DomainValidation;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;

class RecurrenceEntity extends EntityAbstract
{
    public function __construct(
        protected InputNameObject $name,
        protected int $days,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct($this->id);
        $this->validated();
    }

    public function update(
        InputNameObject $name,
        int $days,
    ) {
        $this->name = $name;
        $this->days = $days;
        $this->validated();
    }

    private function validated()
    {
        DomainValidation::numericMin($this->days, 1);
    }
}
