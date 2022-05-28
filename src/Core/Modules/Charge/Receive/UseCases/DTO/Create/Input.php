<?php

namespace Costa\Modules\Charge\UseCases\DTO\Create;

class Input
{
    public function __construct(
        public string $name,
        public float $value,
    ) {
        //
    }
}
