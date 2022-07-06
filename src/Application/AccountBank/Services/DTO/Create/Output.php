<?php

namespace Core\Application\AccountBank\Services\DTO\Create;

class Output
{
    public function __construct(
        public string $name,
        public float $value,
        public string $id,
    ) {
        //
    }
}
