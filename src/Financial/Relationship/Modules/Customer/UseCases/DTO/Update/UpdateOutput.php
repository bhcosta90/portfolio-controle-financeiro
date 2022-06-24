<?php

namespace Core\Financial\Relationship\Modules\Customer\UseCases\DTO\Update;

class UpdateOutput
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
