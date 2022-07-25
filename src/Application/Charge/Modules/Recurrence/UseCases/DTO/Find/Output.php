<?php

namespace Core\Application\Charge\Modules\Recurrence\UseCases\DTO\Find;

class Output
{
    public function __construct(
        public string $name,
        public int    $days,
        public string $id,
    ) {
        //
    }
}
