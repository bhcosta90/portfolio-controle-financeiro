<?php

namespace Core\Financial\Relationship\Modules\Company\UseCases\DTO\Update;

class UpdateInput
{
    public function __construct(
        public string $id,
        public string $name,
        public ?int $document_type,
        public ?string $document_value,
    ) {
        //
    }
}
