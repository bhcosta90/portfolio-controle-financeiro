<?php

namespace App\Services;

use App\Models\Income;
use App\Repositories\Contracts\IncomeRepository as Contract;
use App\Repositories\IncomeRepositoryEloquent as Eloquent;

class IncomeService extends Abstracts\ChargeAbstractService
{
    protected Contract $repository;

    public function __construct(Contract $repository)
    {
        /** @var Eloquent */
        $this->repository = $repository;
    }

    protected function tableName(): string
    {
        return 'incomes';
    }

    protected function modelName(): string
    {
        return Income::class;
    }
}
