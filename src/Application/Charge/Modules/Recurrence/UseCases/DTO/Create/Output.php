<?php

namespace Core\Application\Charge\Modules\Recurrence\UseCases\DTO\Create;

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
