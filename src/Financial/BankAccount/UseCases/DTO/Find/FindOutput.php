<?php

namespace Core\Financial\BankAccount\UseCases\DTO\Find;

class FindOutput
{
    public function __construct(
        public string $id,
        public string $name,
        public float $value,
    ) {
        //
    }
}
