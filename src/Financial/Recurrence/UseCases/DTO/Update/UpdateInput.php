<?php

namespace Core\Financial\Recurrence\UseCases\DTO\Update;

class UpdateInput
{
    public function __construct(
        public string $id,
        public string $name,
        public int $days,
    ) {
        //
    }
}
