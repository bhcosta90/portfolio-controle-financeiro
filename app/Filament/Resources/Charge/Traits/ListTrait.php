<?php

namespace App\Filament\Resources\Charge\Traits;

use App\Models\Charge\Charge;
use App\Models\Enum\Charge\TypeEnum;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait ListTrait
{
    public Carbon $day;

    public function mount(): void
    {
        static::authorizeResourceAccess();

        if (blank($this->activeTab)) {
            $this->activeTab = $this->getDefaultActiveTab();
        }

        $this->day = Carbon::now()->firstOfMonth();
    }

    /**
     * @throws Exception
     */
    public function changeMonth($action): void
    {
        $this->day = match ($action) {
            'addMonth' => $this->day->addMonth(),
            'subMonth' => $this->day->subMonth(),
            default => throw new Exception()
        };
    }

    protected function getTableQuery(): Builder
    {
        $this->generateCharges();

        $query = static::getResource()::getEloquentQuery()->with(['charge']);

        $tabs = $this->getCachedTabs();

        if (
            filled($this->activeTab) &&
            array_key_exists($this->activeTab, $tabs)
        ) {
            $tabs[$this->activeTab]->modifyQuery($query);
        }

        return $query->whereHas('charge', function ($query) {
            $query->whereBetween('due_date', [$this->day->format('Y-m-01'), $this->day->format('Y-m-t')]);
        });
    }

    protected function generateCharges(): void
    {
        $datePrevious = clone $this->day;
        $datePrevious->firstOfMonth()->subMonth();

        $dateActual = clone $this->day;
        $model = app($this->getModel());

        static::getResource()::getEloquentQuery()->with(['charge'])->whereHas(
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
