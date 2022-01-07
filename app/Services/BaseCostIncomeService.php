<?php

namespace App\Services;

use App\Models\Charge;

abstract class BaseCostIncomeService
{
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

        } else {
            $obj[] = $this->repository->createWithCharge($data);
        }

        return collect($obj);
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
