<?php

namespace Core\Financial\Recurrence\UseCases\DTO\Create;

class CreateInput
{
    public function __construct(
        public string $name,
        public int $days,
    ) {
        //
    }
}
