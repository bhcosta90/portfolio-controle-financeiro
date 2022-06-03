<?php

namespace Costa\Modules\Payment\UseCases\DTO\Calculate;

class Output
{
    public function __construct(
        public float $total,
    ) {
        //
    }
}
