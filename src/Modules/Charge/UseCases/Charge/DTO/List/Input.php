<?php

namespace Costa\Modules\Charge\UseCases\Charge\DTO\List;

class Input
{
    public function __construct(
        public ?array $filter = null,
        public ?array $order = null,
        public ?int $total = 25,
        public ?int $page = 1,
    ) {
        //
    }
}
