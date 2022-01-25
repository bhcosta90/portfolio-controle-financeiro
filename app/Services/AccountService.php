<?php

namespace App\Services;

use App\Repositories\AccountRepositoryEloquent as Eloquent;
use App\Repositories\Contracts\AccountRepository as Contract;
use Exception;

class AccountService
{
    private Contract $repository;

    public function __construct(Contract $repository)
    {
        /** @var Eloquent */
        $this->repository = $repository;
    }

    public function getDataIndex()
    {
        return $this->repository;
    }
}
