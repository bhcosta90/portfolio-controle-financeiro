<?php

namespace Costa\Modules\Account\UseCases\Bank\DTO\Update;

class Output
{
    public function __construct(
        public string|int $id,
        public string $name,
    ) {
    }
}
