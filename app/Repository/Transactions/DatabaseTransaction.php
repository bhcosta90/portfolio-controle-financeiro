<?php

namespace App\Repository\Transactions;

use Core\Shared\Interfaces\TransactionInterface;
use Illuminate\Support\Facades\DB;

class DatabaseTransaction implements TransactionInterface
{
    public function __construct()
    {
        DB::beginTransaction();
    }

    public function commit(): void
    {
        DB::commit();
    }

    public function rollback(): void
    {
        DB::rollBack();
    }
}
