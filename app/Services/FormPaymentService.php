<?php

namespace App\Services;

use App\Repositories\Contracts\FormPaymentRepository as Contract;
use App\Repositories\FormPaymentRepositoryEloquent as Eloquent;

class FormPaymentService
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
