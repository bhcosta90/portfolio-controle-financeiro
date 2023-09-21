<?php

namespace App\Filament\Widgets\Charge;

use App\Filament\Resources\Charge\Modules\ReceiveResource;
use App\Filament\Widgets\Charge\Traits\ChargeTrait;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;

class LastReceive extends BaseWidget
{
    use ChargeTrait;

    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $this->last($table, app(ReceiveResource::class));
    }

    protected function getTableHeading(): string|Htmlable|null
    {
        return __('Receita do mês');
    }
}
