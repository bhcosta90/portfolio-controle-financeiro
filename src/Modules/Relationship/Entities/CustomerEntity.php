<?php

namespace Costa\Modules\Relationship\Entities;

use Costa\Shareds\Abstracts\EntityAbstract;
use Costa\Shareds\Validations\DomainValidation;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;

class CustomerEntity extends EntityAbstract
{
    public function __construct(
        protected InputNameObject $name,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }

    public function update(InputNameObject $name)
    {
        $this->name = $name;
    }
}