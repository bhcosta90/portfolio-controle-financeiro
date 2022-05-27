<?php

namespace Costa\Modules\Account\UseCases\Bank\DTO\Find;

class Input
{
    public function __construct(
        public string|int $id,
    ) {
        //
    }
}
