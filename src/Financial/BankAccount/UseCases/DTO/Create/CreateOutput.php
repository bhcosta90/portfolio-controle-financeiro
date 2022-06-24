<?php

namespace Core\Financial\BankAccount\UseCases\DTO\Create;

class CreateOutput
{
    public function __construct(
        public string $id,
        public string $name,
        public string $account,
    ) {
        //
    }
}
