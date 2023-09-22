<?php

namespace App\Filament\Widgets\Charge\Traits;

use App\Services\ChargeService;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait ChargeTrait
{
    public function last(Table $table, Resource $resource): Table
    {
        return $table
            ->query($this->query($resource))
            ->defaultPaginationPageOption(5)
            ->defaultSort('due_date', 'desc')
            ->columns($this->getColumns())
            ->actions($this->getActionByRow($resource))->paginated(false);
    }

    protected function query(Resource $resource): Builder
    {
        $tableModel = $resource::getEloquentQuery()->getModel()->getTable();

        (new ChargeService(app(get_class($resource::getEloquentQuery()->getModel()))))->generate(date: now());

        return $resource::getEloquentQuery()
            ->select("{$tableModel}.*")
            ->whereBetween('charges.due_date', [
                now()->firstOfMonth()->format('Y-m-d'),
                now()->lastOfMonth()->format('Y-m-d')
            ])
            ->whereNull('is_payed')
            ->join('charges', 'charges.charge_id', '=', "{$tableModel}.id");
    }

    public function getColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('charge.description')
                ->label(__('Descrição')),
            Tables\Columns\TextColumn::make('charge.value')
                ->label(__('Valor'))
                ->money(config('money.defaults.currency')),
            Tables\Columns\TextColumn::make('charge.due_date')
                ->label(__('Vencimento'))
                ->date('d/m/Y')
                ->sortable(),
        ];
    }

    public function getActionByRow(Resource $resource): array
    {
        return [
            Tables\Actions\Action::make('open')
                ->label(__('filament-actions::edit.single.label'))
                ->url(fn(Model $record): string => $resource::getUrl('edit', ['record' => $record])),
        ];
    }
}
