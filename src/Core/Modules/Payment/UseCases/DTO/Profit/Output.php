<?php

namespace Costa\Modules\Payment\UseCases\DTO\Profit;

class Output
{
    public function __construct(
        public float $total,
    ) {
        //
    }
}
