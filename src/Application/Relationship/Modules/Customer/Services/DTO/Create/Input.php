<?php

namespace Core\Application\Relationship\Modules\Customer\Services\DTO\Create;

class Input
{
    public function __construct(
        public string $tenant,
        public string $name,
    )
    {
        //
    }
}
