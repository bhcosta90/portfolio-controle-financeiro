<?php

namespace Costa\Modules\Charge\UseCases\DTO\Find;

class Output
{
    public function __construct(
        public string $id,
        public string $name,
        public float $value,
    ) {

    }
}
