<?php

namespace Core\Report\Services\DTO\Generate;

class Input
{
    public function __construct(
        public string $render,
        public array $filter,
    ) {
        //
    }
}
