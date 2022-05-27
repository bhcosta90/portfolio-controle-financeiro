<?php

namespace Costa\Modules\Relationship\UseCases\Customer\DTO\Find;

class Input
{
    public function __construct(
        public int|string $id,
    ) {
        //
    }
}
