<?php

namespace App\Services;

use App\Models\Charge;
use App\Traits\ChargeTrait;
use Carbon\Carbon;
use Costa\LaravelPackage\Utils\Value;

abstract class BaseCostIncomeService
{
    use ChargeTrait;

    abstract protected function tableName(): string;

    abstract protected function modelName(): string;

    public function getDataIndex(array $filters = null)
    {
        $filters['get_due_date'] = empty($filters['date_start']) || empty($filters['date_finish']);

        if (empty($filters['date_start'])) {
            $filters['date_start'] = (new Carbon())->firstOfMonth()->format('Y-m-d');
        }

        if (empty($filters['date_finish'])) {
            $filters['date_finish'] = (new Carbon())->firstOfMonth()->addMonth()->lastOfMonth()->format('Y-m-d');
        }

        return $this->repository
            ->whereHas('charge', function ($obj) use ($filters) {
                $obj->where('user_id', $this->getUser());
                $obj->where(function ($obj) use ($filters) {
                    $obj->whereBetween('due_date', [$filters['date_start'], $filters['date_finish']]);
                    if ($filters['get_due_date']) {
                        $obj->orWhere('due_date', '<=', $filters['date_start']);
                    }
                });
                $obj->where(function ($query) use ($filters) {
                    if (!empty($f = $filters['customer_name'] ?? null)) {
                        $query->where('customer_name', 'like', "%{$f}%");
                    }
                });
                $obj->where('status', Charge::TYPE_PENDING);
            })->join('charges', function ($q) {
                $q->on('charges.chargeable_id', '=', $this->tableName() . '.id')
                    ->where('charges.chargeable_type', $this->modelName());
            })->select($this->tableName() . '.*')
            ->orderBy('charges.due_date');
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

    public function webStore($data)
    {
        if (empty($data['parcel_total']) && empty($data['parcel_total'])) {
            $data['parcel_total'] = 1;
        }
        $data['user_id'] = $data['user_id'] ?? $this->getUser();
        $data['due_date'] = (new Carbon($data['due_date']))->format('d/m/Y');
        return $this->actionStore($data);
    }

    public function actionStore($data, $otherDates = null)
    {
        $data['value_recursive'] = $data['value'];

        if (!empty($data['type'])) {
            $obj = [];

            $dateFinish = !empty($otherDates['_date_finish'])
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

            foreach ($dates as $rsDate) {
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
