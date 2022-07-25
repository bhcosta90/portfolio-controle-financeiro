<?php

namespace Core\Application\BankAccount\Modules\Bank\UseCases\DTO\Create;

class Output
{
    public function __construct(
        public $id,
        public string $tenant,
        public string $name,
        public float $value,
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
