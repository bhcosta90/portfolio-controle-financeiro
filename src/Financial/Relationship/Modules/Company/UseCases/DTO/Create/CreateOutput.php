<?php

namespace Core\Financial\Relationship\Modules\Company\UseCases\DTO\Create;

class CreateOutput
{
    public function __construct(
        public string $id,
        public string $name,
        public string $account,
        public ?int $document_type,
        public ?string $document_value,
    ) {
        //
    }
}
