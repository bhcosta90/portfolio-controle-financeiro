<?php

namespace Costa\Modules\Charge\Receive\UseCases\DTO\Resume;

class Output
{
    public function __construct(
        public int $quantity,
        public float $total,
    ) {
        //
    }
}
