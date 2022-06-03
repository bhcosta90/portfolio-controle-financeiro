<?php

namespace Costa\Shared\Contracts;

interface TransactionContract
{
    public function commit(): void;

    public function rollback(): void;
}
