<?php

namespace Costa\Modules\Account\UseCases\Bank\DTO\Find;

class Output
{
    public function __construct(
        public string $name,
        public string $id,
        public float $value,
    ) {
    }
}
