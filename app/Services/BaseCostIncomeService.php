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
        return $this->repository->whereHas('charge', fn ($obj) => $obj->where('user_id', $this->getUser()));
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

    public function actionStore($data, $otherDates)
    {
        if (!empty($data['type'])) {
            $obj = [];

            $dateFinish = $otherDates['_date_finish']
                ? Carbon::createFromFormat('d/m/Y', $otherDates['_date_finish'])
                : new Carbon();

            $dataTypes = $this->calculate(
                $data['type'],
                Carbon::createFromFormat('d/m/Y', $data['due_date']),
                $dateFinish
            );

            $dataTypesFuture = $this->calculate(
                $data['type'],
                Carbon::createFromFormat('d/m/Y', $data['due_date']),
                $data['type'] == 'every_last_day'
                    ? $dateFinish->firstOfMonth()
                    : $dateFinish->firstOfMonth()->addMonth(),
                ['first_date' => false]
            );

            $dates = array_merge($dataTypes, array_map(fn ($x) => $x += ['future' => true], $dataTypesFuture));
            $nDates = [];
            foreach ($dates as $date) {
                $nDates[$date['date_original'] . $date['date_week']] = $date;
            }
            $dates = array_values($nDates);

            foreach($dates as $rsDate) {
                $obj[] = $this->repository->createWithCharge([
                    'future' => $rsDate['future'] ?? false,
                    'due_date' => $rsDate['date_week'],
                    'type' => $data['type'],
                ] + $data);
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

    protected function getUser(): int
    {
        return auth()->user()->id;
    }

    protected function getValue()
    {
        return new Value;
    }
}
