<?php

namespace Core\Application\Relationship\Modules\Customer\UseCases\DTO\Find;

class Output
{
    public function __construct(
        public string $name,
        public string $id,
    ) {
        //
    }
}
