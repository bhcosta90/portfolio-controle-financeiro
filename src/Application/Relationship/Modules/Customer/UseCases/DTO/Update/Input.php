<?php

namespace Core\Application\Relationship\Modules\Customer\UseCases\DTO\Update;

class Input
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
        //
    }
}
