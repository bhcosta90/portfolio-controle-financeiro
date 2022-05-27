<?php

namespace Costa\Modules\Relationship\UseCases\DTO\Create;

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
