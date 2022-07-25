<?php

namespace Core\Application\Relationship\Modules\Customer\UseCases\DTO\Create;

class Input
{
    public function __construct(
        public string $tenant,
        public string $name,
    ) {
        //
    }
}
