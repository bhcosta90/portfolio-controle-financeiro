<?php

namespace Core\Application\AccountBank\Services\DTO\Update;

class Input
{
    public function __construct(
        public string $id,
        public string $name,
        public float  $value,
    )
    {
        //
    }
}
