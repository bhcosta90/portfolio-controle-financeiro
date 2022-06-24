<?php

namespace Core\Financial\Recurrence\UseCases\DTO\Update;

class UpdateOutput
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
        //
    }
}
