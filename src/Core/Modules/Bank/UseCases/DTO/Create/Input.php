<?php

namespace Costa\Modules\Bank\UseCases\DTO\Create;

class Input
{
    public function __construct(
        public string $name,
        public float $value,
    ) {
        //
    }
}
