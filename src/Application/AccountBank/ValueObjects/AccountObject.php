<?php

namespace Core\Application\AccountBank\ValueObjects;

class AccountObject
{
    public function __construct(
        public string $account,
        public ?int   $digit = null,
    )
    {
    }
}
