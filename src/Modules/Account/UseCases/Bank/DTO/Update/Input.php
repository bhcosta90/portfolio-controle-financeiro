<?php

namespace Costa\Modules\Account\UseCases\Bank\DTO\Update;

class Input
{
    public function __construct(
        public string|int $id,
        public string $name,
        public bool $active,
        public float $value,
    ) {
        //
    }
}
