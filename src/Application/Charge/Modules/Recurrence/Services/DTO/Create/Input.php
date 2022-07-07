<?php

namespace Core\Application\Charge\Modules\Recurrence\Services\DTO\Create;

class Input
{
    public function __construct(
        public string $tenant,
        public string $name,
        public int    $days,
    )
    {
        //
    }
}
