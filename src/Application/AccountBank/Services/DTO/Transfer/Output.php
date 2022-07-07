<?php

namespace Core\Application\AccountBank\Services\DTO\Transfer;

class Output
{
    public function __construct(
        public string $idPayment,
        public string  $idReceive,
    ) {
        //
    }
}
