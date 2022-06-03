<?php

namespace Costa\Modules\Relationship\Customer\Entity;

use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\ValueObject\DocumentObject;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\UuidObject;

class CustomerEntity extends EntityAbstract
{
    public function __construct(
        protected InputNameObject $name,
        protected ?DocumentObject $document,
        protected ?UuidObject $id = null,
    ) {
        parent::__construct();
    }

    public function update(
        InputNameObject $name,
        ?DocumentObject $document,
    ) {
        $this->name = $name;
        $this->document = $document;
    }
}
