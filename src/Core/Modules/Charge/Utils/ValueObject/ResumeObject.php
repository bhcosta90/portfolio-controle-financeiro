<?php

namespace Costa\Modules\Charge\Utils\ValueObject;

class ResumeObject
{
    public function __construct(
        public int $quantity,
        public float $total,
    ) {
        //
    }
}
