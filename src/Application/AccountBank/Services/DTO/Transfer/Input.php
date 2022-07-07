<?php

namespace Core\Application\AccountBank\Services\DTO\Transfer;

class Input
{
    public function __construct(
        public string $tenant,
        public string $idBankFrom,
        public string $idBankTo,
        public float  $value,
    ) {
        //
    }
}
