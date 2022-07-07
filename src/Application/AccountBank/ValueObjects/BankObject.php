<?php

namespace Core\Application\AccountBank\ValueObjects;

class BankObject
{
    public function __construct(
        public               $code,
        public AccountObject $agency,
        public AccountObject $account,
    )
    {
        //
    }
}
