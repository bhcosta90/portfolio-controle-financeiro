<?php

namespace Costa\Modules\Charge\Shareds\ValueObjects;

class ResumeObject
{
    public function __construct(
        public int $quantity,
        public float $total,
    ) {
        //
    }
}
