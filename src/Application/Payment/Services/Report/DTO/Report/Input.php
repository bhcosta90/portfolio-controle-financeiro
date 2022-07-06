<?php

namespace Core\Application\Payment\Services\Report\DTO\Report;

class Input
{
    public function __construct(
        public array $filter,
        public string $title,
        public ?string $type = 'html',
        public ?string $subtitle = null,
    ) {
        //
    }
}
