<?php

namespace Costa\Shareds\Contracts;

interface TransactionContract
{
    public function commit(): void;

    public function rollback(): void;
}
