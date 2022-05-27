<?php

namespace Costa\Modules\Charge\UseCases\Recurrence\DTO\Find;

class Input
{
    public function __construct(
        public string|int $id,
    ) {
        //
    }
}
