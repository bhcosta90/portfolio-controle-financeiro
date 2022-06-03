<?php

namespace Costa\Modules\Relationship\Supplier\UseCases\DTO\List;

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