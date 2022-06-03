<?php

namespace Costa\Shared\Contracts;

interface EventInterface
{
    public function getEventName(): string;

    public function getPayload(): array;
}
