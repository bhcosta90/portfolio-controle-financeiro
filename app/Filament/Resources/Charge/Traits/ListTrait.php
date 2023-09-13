<?php

namespace App\Filament\Resources\Charge\Traits;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;

trait ListTrait
{
    public Carbon $day;

    public function mount(): void
    {
        static::authorizeResourceAccess();

        if (blank($this->activeTab)) {
            $this->activeTab = $this->getDefaultActiveTab();
        }

        $this->day = now()->firstOfMonth();
    }

    /**
     * @throws Exception
     */
    public function changeMonth($action)
    {
        $this->day = match ($action) {
            'addMonth' => $this->day->addMonth(),
            'subMonth' => $this->day->subMonth(),
            default => throw new Exception()
        };
    }

    protected function getTableQuery(): Builder
    {
        $query = static::getResource()::getEloquentQuery();

        $tabs = $this->getCachedTabs();

        if (
            filled($this->activeTab) &&
            array_key_exists($this->activeTab, $tabs)
        ) {
            $tabs[$this->activeTab]->modifyQuery($query);
        }

        return $query->whereHas('charge', function ($query) {
            $query->whereBetween('due_date', [$this->day->format('Y-m-d'), $this->day->format('Y-m-t')]);
        });
    }
}
