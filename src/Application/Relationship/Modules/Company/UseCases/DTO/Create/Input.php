<?php

namespace Core\Application\Relationship\Modules\Company\UseCases\DTO\Create;

class Input
{
    public function __construct(
        public string $tenant,
        public string $name,
    ) {
        //
    }
}
