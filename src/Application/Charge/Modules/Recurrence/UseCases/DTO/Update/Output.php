<?php

namespace Core\Application\Charge\Modules\Recurrence\UseCases\DTO\Update;

class Output
{
    public function __construct(
        public string $name,
        public string $id,
    ) {
        //
    }
}
