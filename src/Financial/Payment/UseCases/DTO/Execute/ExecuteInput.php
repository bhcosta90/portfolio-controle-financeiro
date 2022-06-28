<?php

namespace Core\Financial\Payment\UseCases\DTO\Execute;

class ExecuteInput
{
    public function __construct(
        public string $date,
    ) {
        //
    }
}
