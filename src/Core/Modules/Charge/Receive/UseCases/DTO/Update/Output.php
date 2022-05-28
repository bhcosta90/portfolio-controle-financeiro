<?php

namespace Costa\Modules\Charge\Receive\UseCases\DTO\Update;

class Output
{
    public function __construct(
        public int|string $id,
    ) {

    }
}
