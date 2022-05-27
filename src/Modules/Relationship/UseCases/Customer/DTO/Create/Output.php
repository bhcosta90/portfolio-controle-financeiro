<?php

namespace Costa\Modules\Relationship\UseCases\Customer\DTO\Create;

class Output
{
    public function __construct(
        public string $name,
        public string|int $id,
    ) {
        //
    }
}
