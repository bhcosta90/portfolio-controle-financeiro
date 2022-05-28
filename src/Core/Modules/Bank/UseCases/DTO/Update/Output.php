<?php

namespace Costa\Modules\Bank\UseCases\DTO\Update;

class Output
{
    public function __construct(
        public string $id,
        public string $name,
        public float $value,
    ) {

    }
}
