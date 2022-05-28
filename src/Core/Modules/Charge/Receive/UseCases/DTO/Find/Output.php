<?php

namespace Costa\Modules\Charge\Receive\UseCases\DTO\Find;

class Output
{
    public function __construct(
        public int|string $id,
        public string $name,
        public float $value,
    ) {

    }
}
