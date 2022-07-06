<?php

namespace Core\Application\AccountBank\Services\DTO\Create;

class Input
{
    public function __construct(
        public string $name,
        public float $value,
    ) {
        //
    }
}
