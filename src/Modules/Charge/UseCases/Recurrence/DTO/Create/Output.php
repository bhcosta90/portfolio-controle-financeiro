<?php

namespace Costa\Modules\Charge\UseCases\Recurrence\DTO\Create;

class Output
{
    public function __construct(
        public string|int $id,
        public string $name,
        public int $days,
    ) {
    }
}
