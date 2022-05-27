<?php

namespace Costa\Modules\Account\UseCases\Bank\DTO\Create;

use Costa\Modules\Account\ValueObjects\BankObject;

class Output
{
    public function __construct(
        public string|int $id,
        public string $name,
        public ?BankObject $bank = null,
        public ?float $value = 0,
    ) {
    }
}
