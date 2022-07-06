<?php

namespace Core\Application\Relationship\Modules\Customer\Services\DTO\Update;

class Input
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
        //
    }
}
