<?php

namespace Costa\Modules\Bank\UseCases\DTO\Create;

class Output
{
    public function __construct(
        public string $id,
        public string $name,
        public float $value,
    ) {
    }
}
