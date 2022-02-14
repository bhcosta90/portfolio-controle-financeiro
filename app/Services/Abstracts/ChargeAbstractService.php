<?php

namespace App\Services\Abstracts;

use App\Models\Charge;
use App\Services\ChargeService;
use App\Services\RecurrencyService;
use Carbon\Carbon;
use Costa\LaravelPackage\Utils\Recursive;
use Illuminate\Support\Str;

abstract class ChargeAbstractService
{

    protected abstract function tableName(): string;

    protected abstract function modelName(): string;

    public function data($filters)
    {
        $filters['get_due_date'] = true;

        if (empty($filters['date_start'])) {
            $filters['date_start'] = (new Carbon())->firstOfMonth()->format('Y-m-d');
        }

        if (empty($filters['type'])) {
            $filters['type'] = 0;
        }

        if (empty($filters['date_finish'])) {
            $filters['date_finish'] = (new Carbon())->firstOfMonth()->lastOfMonth()->format('Y-m-d');
        }

        $filters['date_finish'] = new Carbon($filters['date_finish']);

        if ($filters['type'] == 2) {
            $filters['date_finish'] = $filters['date_finish']->firstOfMonth()->addMonth()->lastOfMonth();
        }

        $filters['date_finish'] = $filters['date_finish']->format('Y-m-d');

        $result = $this->repository->whereHas('charge', function ($obj) use ($filters) {
            $obj->whereIn('user_id', $filters['user']->getSharedIdUser());
            $obj->where(function ($obj) use ($filters) {
                $obj->whereBetween('due_date', [$filters['date_start'], $filters['date_finish']]);
                if (in_array($filters['type'], [0, 2])) {
                    $obj->orWhere('due_date', '<=', $filters['date_start']);
                }
            });

            $obj->where(function ($query) use ($filters) {
                if (!empty($f = $filters['customer_name'] ?? null)) {
                    $query->where('customer_name', 'like', "%{$f}%");
                }
            });

            if ($filters['type'] === false) {
                $obj->where('type', $filters['type']);
            }
        })->join('charges', function ($q) {
            $q->on('charges.chargeable_id', '=', $this->tableName() . '.id')
                ->where('charges.chargeable_type', $this->modelName());
        })->select($this->tableName() . '.*')
            ->where('status', Charge::$STATUS_PENDING)
            ->whereNull('deleted_at')
            ->with('charge.recurrency', 'parcelsActive')
            ->orderBy('charges.date_start');

        return $result;
    }

    public function webStore($data)
    {
        if (!empty($data['recurrency']) && $data['recurrency'] > 0) {

            $type = $this->getRecurrencyService()->getById($data['recurrency']);

            /** @var Recursive $objRecursive */
            $dates = app(Recursive::class)->calculate(
                $type->type,
                (new Carbon($data['due_date'])),
                (new Carbon($data['due_date']))->addMonth()->lastOfMonth(),
                ['first_date' => true]
            );

            $data['due_date'] = $dates[0]['date_week'];
        }

        $data['value'] = Str::numberBrToEn($data['value']);

        return $this->getChargeService()->store($this->repository, $data);
    }

    /**
     * @return ChargeService
     */
    protected function getChargeService()
    {
        return app(ChargeService::class);
    }

    /**
     * @return RecurrencyService
     */
    protected function getRecurrencyService()
    {
        return app(RecurrencyService::class);
    }
}
