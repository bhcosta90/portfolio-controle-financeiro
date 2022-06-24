<?php

namespace Core\Financial\BankAccount\UseCases\DTO\Update;

class UpdateOutput
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
        //
    }
}
