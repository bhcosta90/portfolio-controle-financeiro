<?php

namespace Costa\Modules\Relationship\Customer\UseCases\DTO\Create;

class Output
{
    public function __construct(
        public int|string $id,
        public string $name,
        public ?int $document_type = null,
        public ?int $document_value = null,
    ) {
    }
}
