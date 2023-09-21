<?php

namespace App\Filament\Widgets\Charge;

use App\Filament\Resources\Charge\Modules\PaymentResource;
use App\Filament\Widgets\Charge\Traits\ChargeTrait;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;

class LastPayment extends BaseWidget
{
    use ChargeTrait;

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected function getTableHeading(): string|Htmlable|null
    {
        return __('Despesas do mês');
    }

    public function table(Table $table): Table
    {
        return $this->last($table, app(PaymentResource::class));
    }
}
