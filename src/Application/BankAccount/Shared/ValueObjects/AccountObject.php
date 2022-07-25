<?php

namespace Core\Application\BankAccount\Shared\ValueObjects;

class AccountObject
{
    public function __construct(
        public string $account,
        public ?int $digit = null,
    ) {
        //
    }
}
