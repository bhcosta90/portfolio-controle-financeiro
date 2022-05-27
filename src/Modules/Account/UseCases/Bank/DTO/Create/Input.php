<?php

namespace Costa\Modules\Account\UseCases\Bank\DTO\Create;

use Costa\Modules\Account\ValueObjects\BankObject;

class Input
{
    public function __construct(
        public string $name,
        public bool $active,
        public ?BankObject $bank = null,
        public ?float $value = 0,
    ) {
        //
    }
}
