<?php

namespace App\Services;

use App\Models\Charge;
use App\Repositories\ChargeRepositoryEloquent as Eloquent;
use App\Repositories\Contracts\ChargeRepository as Contract;
use Exception;
use Illuminate\Support\Facades\DB;

class ChargeService
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

    public function getBy($uuid)
    {
        return $this->repository->where('uuid', $uuid)->first();
    }

    public function webUpdate($id, $data)
    {
        return $this->repository->update($data, $id);
    }

    public function pay($id, $data)
    {
        $obj = $this->getBy($id);

        return DB::transaction(function () use ($obj, $data) {
            return $this->repository->update($data + [
                'status' => Charge::STATUS_PAYED
            ], $obj->id);
        });
    }
}
