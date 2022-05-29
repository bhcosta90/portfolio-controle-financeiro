<?php

namespace Costa\Modules\Charge\Payment\UseCases\DTO\List;

class Input
{
    public function __construct(
        public ?array $filter = null,
        public ?int $total = 25,
        public ?int $page = 1,
    ) {
        //
    }
}
