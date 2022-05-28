<?php

namespace Costa\Modules\Relationship\Supplier\UseCases\DTO\Create;

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
