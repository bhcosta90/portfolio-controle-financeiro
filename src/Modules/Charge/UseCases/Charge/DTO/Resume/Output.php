<?php

namespace Costa\Modules\Charge\UseCases\Charge\DTO\Resume;

class Output
{
    public function __construct(
        public int $quantity,
        public float $total,
    ) {
        //
    }
}
