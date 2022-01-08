<?php

namespace App\Services;

use App\Models\Charge;
use Costa\Package\Traits\Api\Value;

abstract class BaseCostIncomeService
{
    protected Value $valueUtil;

    public function __construct(Value $valueUtil)
    {
        $this->valueUtil = $valueUtil;
    }


    public function getDataIndex()
    {
        return $this->repository->whereHas('charge', fn ($obj) => $obj->where('user_id', $this->getUser()->id));
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
        if (!empty($data['parcel_total'])) {
            dd($this->valueUtil->calculateParcel((float) $data['value'], (int) $data['parcel_total']));
        }

        return collect([$this->repository->createWithCharge($data)]);
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
