<?php

namespace Costa\Modules\Relationship\UseCases\Supplier\DTO\Update;

class Input
{
    public function __construct(
        public string|int $id,
        public string $name,
    ) {
        //
    }
}
