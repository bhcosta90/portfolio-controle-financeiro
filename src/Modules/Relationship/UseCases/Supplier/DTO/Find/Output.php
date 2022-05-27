<?php

namespace Costa\Modules\Relationship\UseCases\Supplier\DTO\Find;

class Output
{
    public function __construct(
        public string $name,
        public string|int $id,
    ) {
        //
    }
}
