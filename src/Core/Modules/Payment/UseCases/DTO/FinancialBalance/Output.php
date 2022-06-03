<?php

namespace Costa\Modules\Payment\UseCases\DTO\FinancialBalance;

class Output
{
    public function __construct(
        public float $total,
    ) {
        //
    }
}
