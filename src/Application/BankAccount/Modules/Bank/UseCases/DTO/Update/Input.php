<?php

namespace Core\Application\BankAccount\Modules\Bank\UseCases\DTO\Update;

class Input
{
    public function __construct(
        public string $id,
        public string $name,
        public float $value,
        public float $active,
        public ?string $bankCode = null,
        public ?string $agency = null,
        public ?string $agencyDigit = null,
        public ?string $account = null,
        public ?string $accountDigit = null,
        public ?string $accountEntity = null,
    ) {
        //
    }
}
