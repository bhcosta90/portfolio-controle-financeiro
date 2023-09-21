<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Charge\Modules\ReceiveResource;
use App\Filament\Widgets\Traits\ChargeTrait;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;

class LastReceive extends BaseWidget
{
    use ChargeTrait;

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected function getTableHeading(): string|Htmlable|null
    {
        return __('Receita do mês');
    }

    public function table(Table $table): Table
    {
        return $this->last($table, app(ReceiveResource::class));
    }
}
