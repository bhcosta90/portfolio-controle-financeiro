<?php

namespace Costa\Modules\Relationship;

use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\ValueObject\{DocumentObject, UuidObject, Input\InputNameObject};

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
