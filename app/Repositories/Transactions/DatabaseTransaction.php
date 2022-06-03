<?php

namespace App\Repositories\Transactions;

use Costa\Shared\Contracts\TransactionContract;
use Illuminate\Support\Facades\DB;

class DatabaseTransaction implements TransactionContract
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