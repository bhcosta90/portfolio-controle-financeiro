<?php

namespace Costa\Modules\Payment\UseCases\DTO\Resume;

class Output
{
    public function __construct(
        public float $value,
        public float $calculate,
    ) {
        //
    }
}
