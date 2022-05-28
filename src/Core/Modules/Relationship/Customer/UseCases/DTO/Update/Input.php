<?php

namespace Costa\Modules\Relationship\Customer\UseCases\DTO\Update;

class Input
{
    public function __construct(
        public string $id,
        public string $name,
        public ?int $documentType = null,
        public ?string $documentValue = null,
    ) {
        //
    }
}
