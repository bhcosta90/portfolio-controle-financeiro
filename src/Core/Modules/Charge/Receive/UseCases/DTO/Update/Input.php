<?php

namespace Costa\Modules\Charge\Receive\UseCases\DTO\Update;

class Input
{
    public function __construct(
        public string $id,
        public float $value,
    ) {
        //
    }
}
