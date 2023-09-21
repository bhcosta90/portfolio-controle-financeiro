<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Charge\ReceiveResource;
use App\Models\Charge\Receive;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;

class LastReceive extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected function getTableHeading(): string|Htmlable|null
    {
        return __('Receita do mês');
    }

    public function table(Table $table): Table
    {
        $tableModel = ReceiveResource::getEloquentQuery()->getModel()->getTable();

        return $table
            ->query(
                ReceiveResource::getEloquentQuery()
                    ->select("{$tableModel}.*")
                    ->whereBetween('charges.due_date', [
                        now()->firstOfMonth()->format('Y-m-d'),
                        now()->lastOfMonth()->format('Y-m-d')
                    ])
                    ->join('charges', 'charges.charge_id', '=', "{$tableModel}.id")
            )
            ->defaultPaginationPageOption(5)
            ->defaultSort('due_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('charge.description')
                    ->label(__('Descrição')),
                Tables\Columns\TextColumn::make('charge.value')
                    ->label(__('Valor'))
                    ->money(config('money.defaults.currency')),
                Tables\Columns\TextColumn::make('charge.due_date')
                    ->label(__('Vencimento'))
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->label(__('filament-actions::edit.single.label'))
                    ->url(fn (Receive $record): string => ReceiveResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
