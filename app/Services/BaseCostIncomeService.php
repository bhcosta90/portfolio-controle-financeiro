<?php

namespace App\Services;

use App\Models\Charge;
use App\Traits\ChargeTrait;
use Carbon\Carbon;
use Costa\LaravelPackage\Utils\Value;

abstract class BaseCostIncomeService
{
    use ChargeTrait;

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
        if (!empty($data['type'])) {
            $obj = [];

            $dataTypes = $this->calculate(
                $data['type'],
                Carbon::createFromFormat('d/m/Y', $data['due_date']),
                new Carbon
            );

            $dateLast = null;
            foreach ($dataTypes['date_week'] as $type) {
                $obj[] = $this->repository->createWithCharge([
                    'due_date' => $type,
                    'type' => $data['type'],
                ] + $data);

                $dateLast = $type;
            }

            $dataTypes = $this->calculate(
                $data['type'],
                new Carbon($dateLast),
                (new Carbon($dateLast))->addMonth(),
                ['first_date' => false]
            );

            foreach ($dataTypes['date_week'] as $type) {
                $obj[] = $this->repository->createWithCharge([
                    'future' => true,
                    'due_date' => $type,
                    'type' => $data['type'],
                ] + $data);

                $dateLast = $type;
            }

            return collect($obj);
        }

        if (!empty($data['parcel_total'])) {
            $obj = [];
            $parcels = $this->getValue()->parcel(
                Carbon::createFromFormat('d/m/Y', $data['due_date']),
                (float) $data['value'],
                (int) $data['parcel_total']
            );

            foreach ($parcels as $k => $valueParcel) {
                $obj[] = $this->repository->createWithCharge($valueParcel + $data + [
                    'parcel_actual' => $k + 1,
                    'parcel_total' => (int) $data['parcel_total'],
                ]);
            }

            return collect($obj);
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

    private function getValue()
    {
        return new Value;
    }
}
