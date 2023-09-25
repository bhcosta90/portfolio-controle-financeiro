<?php

namespace App\Filament\Resources\Charge\Traits;

use App\Jobs\Charge\GenerateChargeNextMonthJob;
use App\Jobs\Charge\Test;
use App\Services\ChargeService;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;

trait ListTrait
{
    public Carbon $date;

    public function mount(): void
    {
        static::authorizeResourceAccess();

        if (blank($this->activeTab)) {
            $this->activeTab = $this->getDefaultActiveTab();
        }

        $this->date = Carbon::now()->firstOfMonth();
    }

    /**
     * @throws Exception
     */
    public function changeMonth($action): void
    {
        $this->date = match ($action) {
            'addMonth' => $this->date->addMonth(),
            'subMonth' => $this->date->subMonth(),
            default => throw new Exception()
        };
    }

    protected function getTableQuery(): Builder
    {
        (new ChargeService(app($this->getModel())))->generate(date: $this->date);

        $query = static::getResource()::getEloquentQuery()->with(['charge']);

        $tabs = $this->getCachedTabs();

        if (
            filled($this->activeTab) &&
            array_key_exists($this->activeTab, $tabs)
        ) {
            $tabs[$this->activeTab]->modifyQuery($query);
        }

        return $query->whereHas('charge', function ($query) {
            $query->whereBetween('due_date', [$this->date->format('Y-m-01'), $this->date->format('Y-m-t')]);
        });
    }
}
