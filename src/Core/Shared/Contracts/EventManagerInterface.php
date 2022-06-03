<?php

namespace Costa\Shared\Contracts;

interface EventManagerInterface
{
    public function dispatch(object $data): void;
}
