<?php

namespace Core\Financial\BankAccount\UseCases\DTO\Create;

class CreateInput
{
    public function __construct(
        public string $name,
        public float $value,
    ) {
        //
    }
}
