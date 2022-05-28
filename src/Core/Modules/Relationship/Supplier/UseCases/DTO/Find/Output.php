<?php

namespace Costa\Modules\Relationship\Supplier\UseCases\DTO\Find;

class Output
{
    public function __construct(
        public string $id,
        public string $name,
        public ?int $document_type = null,
        public ?int $document_value = null,
    ) {

    }
}
