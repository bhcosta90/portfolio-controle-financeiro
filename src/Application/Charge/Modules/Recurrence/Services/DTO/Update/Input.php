<?php

namespace Core\Application\Charge\Modules\Recurrence\Services\DTO\Update;

class Input
{
    public function __construct(
        public string $id,
        public string $name,
        public int $days,
    ) {
        //
    }
}
