<?php

namespace Core\Financial\Relationship\Modules\Customer\UseCases\DTO\Create;

class CreateInput
{
    public function __construct(
        public string $name,
        public ?int $document_type,
        public ?string $document_value,
    ) {
        //
    }
}
