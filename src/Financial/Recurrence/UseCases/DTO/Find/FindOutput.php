<?php

namespace Core\Financial\Recurrence\UseCases\DTO\Find;

class FindOutput
{
    public function __construct(
        public string $id,
        public string $name,
        public int $days,
    ) {
        //
    }
}
