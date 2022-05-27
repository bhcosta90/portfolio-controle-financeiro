<?php

namespace Costa\Modules\Account\ValueObjects;

use Costa\Shareds\ValueObjects\DocumentObject;

class BankObject
{
    public function __construct(
        public string $code,
        public string $agency,
        public string $account,
        public ?DocumentObject $document,
        public ?string $agencyDigit = null,
        public ?string $accountDigit = null,
    ) {
        //
    }
}
