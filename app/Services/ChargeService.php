<?php

namespace App\Services;

use App\Models\Charge\Charge;
use App\Models\Enum\Charge\TypeEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ChargeService
{
    public function __construct(protected string $model)
    {
        //
    }

    public function generate(Carbon $date): void
    {
        $dateActual = clone $date;
        $datePrevious = clone $date;

        $datePrevious->firstOfMonth()->subMonth();

        $model = app($this->model);

        $model->with(['charge'])->whereHas(
            'charge',
            function ($query) use ($datePrevious) {
                $query->where(function ($query) use ($datePrevious) {
                    $query->where(function ($query) use ($datePrevious) {
                        $query->whereNull('deleted_at');
                    })
                        ->orWhere(function ($query) use ($datePrevious) {
                            $query->whereNotNull('deleted_at')
                                ->whereIsDeleted(true);
                        });
                })->withTrashed()
                    ->whereBetween(
                        'due_date',
                        [$datePrevious->format('Y-m-01'), $datePrevious->format('Y-m-t')]
                    )
                    ->whereType(TypeEnum::MONTHLY->value);
            }
        )->withTrashed()->chunk(100, function ($data) use ($dateActual, $model) {
            DB::transaction(function () use ($data, $dateActual, $model) {
                foreach ($data as $rs) {
                    $charge = $rs->charge()->withTrashed()->firstOrFail();

                    $total = Charge::whereBetween(
                        'due_date',
                        [$dateActual->format('Y-m-01'), $dateActual->format('Y-m-t')]
                    )
                        ->whereGroupId($charge->group_id)
                        ->withTrashed()
                        ->count();

                    if ($total === 0) {
                        $dateNewCharge = $dateActual->setDay($charge->day_charge);
                        if ($dateNewCharge->format('m') != $dateActual->format('m')) {
                            $dateNewCharge->subMonth()->lastOfMonth();
                        }

                        $chargeCreate = $model->create();
                        $chargeCreate->charge()->create(
                            [
                                'due_date' => $dateNewCharge->format('Y-m-d'),
                                'day_charge' => $charge->day_charge,
                            ] + $charge->toArray()
                        );
                    }
                }
            });
        });
    }
}
