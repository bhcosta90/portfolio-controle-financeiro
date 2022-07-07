<?php

namespace Core\Application\AccountBank\Services\DTO\Update;

class Output
{
    public function __construct(
        public string $name,
        public float  $value,
        public string $id,
    )
    {
        //
    }
}
