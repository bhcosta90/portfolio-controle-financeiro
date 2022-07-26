<?php

namespace Core\Application\Charge\Modules\Payment\UseCases\DTO\List;

class Output
{
    public function __construct(
        public array $items,
        public int $total,
        public int $last_page,
        public int $first_page,
        public int $per_page,
        public int $to,
        public int $from,
        public int $current_page,
        public array $filter,
        public float $value,
    ) {
        //
    }
}
