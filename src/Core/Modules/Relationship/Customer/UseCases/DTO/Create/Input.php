<?php

namespace Costa\Modules\Relationship\Customer\UseCases\DTO\Create;

class Input
{
    public function __construct(
        public string $name,
        public ?int $documentType = null,
        public ?string $documentValue = null,
    ) {
        //
    }
}
