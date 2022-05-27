<?php

namespace Costa\Modules\Relationship\UseCases\DTO\Create;

use Costa\Shared\ValueObject\DocumentObject;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\UuidObject;

class Output
{
    public function __construct(
        public UuidObject $id,
        public InputNameObject $name,
        public ?DocumentObject $document,
    ) {

    }
}
