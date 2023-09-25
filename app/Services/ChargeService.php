<?php

namespace App\Services;

use App\Models\Charge\Charge;
use App\Models\Charge\Payment;
use App\Models\Charge\Receive;
use App\Models\Enum\Charge\TypeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChargeService
{
    public function __construct(protected Model $model)
    {
        //
    }

    public function generate(Carbon $date): void
    {
        list($dateActual, $model, $query) = $this->queryByGenerate($date);

        $query->chunk(100, function ($data) use ($dateActual, $model) {
            DB::transaction(function () use ($data, $dateActual, $model) {
                foreach ($data as $rs) {
                    $this->createCharge($rs, $dateActual, $model);
                }
            });
        });
    }

    public function payed(Charge $charge)
    {
        $charge->account->updateVersion();

        if ($charge->is_payed) {
            
            $charge->extract()->create([
                'value' => $charge->value,
                'charge_type' => get_class($charge->charge),
                'account_id' => $charge->account_id,
                'account_version' => $charge->account->version
            ]);
        } else if ($charge->extract) {
            $charge->extract->update([
                'account_version' => $charge->account->version
            ]);
            
            $charge->extract->delete();
        }
    }

    protected function queryByGenerate(Carbon $date): array
    {
        $dateActual = clone $date;
        $datePrevious = clone $date;

        $datePrevious->firstOfMonth()->subMonth();

        $model = $this->model;

        $query = $model->with(['charge'])->whereHas(
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
        )->withTrashed();
        return array($dateActual, $model, $query);
    }

    protected function createCharge(Payment|Receive $rs, Carbon $dateActual): void
    {
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

            $chargeCreate = app(get_class($rs))->create();
            $chargeCreate->charge()->create(
                [
                    'due_date' => $dateNewCharge->format('Y-m-d'),
                    'day_charge' => $charge->day_charge,
                ] + $charge->toArray()
            );
        }
    }
}
