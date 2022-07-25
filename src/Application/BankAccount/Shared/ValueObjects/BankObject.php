<?php

namespace Core\Application\BankAccount\Shared\ValueObjects;

class BankObject
{
    public function __construct(
        public $code,
        public AccountObject $agency,
        public AccountObject $account,
    ) {
        //
    }
}
