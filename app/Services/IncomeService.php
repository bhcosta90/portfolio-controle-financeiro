<?php

namespace App\Services;

use App\Models\Charge;
use App\Repositories\Contracts\IncomeRepository as Contract;
use App\Repositories\IncomeRepositoryEloquent as Eloquent;
use Exception;

class IncomeService
{
    private Contract $repository;

    public function __construct(Contract $repository)
    {
        /** @var Eloquent */
        $this->repository = $repository;
    }

    public function getDataIndex()
    {
        return $this->repository->whereHas('charge', fn($obj) => $obj->where('user_id', $this->getUser()->id));
    }

    public function deleteBy($id)
    {
        $obj = $this->getBy($id);
        $obj->charge()->delete();
        $obj->delete();
    }

    public function getBy($id)
    {
        return Charge::where('uuid', $id)->firstOrFail()->chargeable;
    }

    public function actionStore($data)
    {
        $obj = $this->repository->createWithCharge($data);
        return collect([$obj]);
    }

    public function actionUpdate($id, $data)
    {
        $obj = $this->getBy($id);
        $obj->charge->update($data);
        return $obj;
    }

    private function getUser()
    {
        return auth()->user();
    }
}
