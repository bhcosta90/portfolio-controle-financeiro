<?php

namespace Core\Financial\BankAccount\UseCases\DTO\Update;

class UpdateInput
{
    public function __construct(
        public string $id,
        public string $name,
        public float $value,
    ) {
        //
    }
}
