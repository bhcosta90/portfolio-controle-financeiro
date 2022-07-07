<?php

namespace Core\Application\Report\Services\DTO\Generate;

class Input
{
    public function __construct(
        public string $render,
        public array  $filter,
    )
    {
        //
    }
}
