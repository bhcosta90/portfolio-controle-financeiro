<?php

namespace Core\Financial\Recurrence\UseCases\DTO\Create;

class CreateOutput
{
    public function __construct(
        public string $id,
        public string $name,
        public int $days,
    ) {
        //
    }
}
