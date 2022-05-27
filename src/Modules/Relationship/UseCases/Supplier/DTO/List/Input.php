<?php

namespace Costa\Modules\Relationship\UseCases\Supplier\DTO\List;

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
