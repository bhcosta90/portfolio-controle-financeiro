<?php

namespace Core\Application\Payment\Services\Report\DTO\Report;

class Header
{
    public function __construct(
        public string $relationship,
        public string $title,
        public string $bank,
        public string $value,
    ) {
        //
    }
}
