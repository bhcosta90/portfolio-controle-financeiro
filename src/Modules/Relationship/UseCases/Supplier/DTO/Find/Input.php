<?php

namespace Costa\Modules\Relationship\UseCases\Supplier\DTO\Find;

class Input
{
    public function __construct(
        public int|string $id,
    ) {
        //
    }
}
