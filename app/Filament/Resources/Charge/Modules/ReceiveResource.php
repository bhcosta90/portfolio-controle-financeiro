<?php

namespace App\Filament\Resources\Charge\Modules;

use App\Filament\Resources\Charge\Modules;
use App\Filament\Resources\Charge\ReceiveResource\Pages;
use App\Filament\Resources\Charge\ReceiveResource\RelationManagers;
use App\Filament\Resources\Charge\Traits\ResourceTrait;
use App\Models\Charge\Receive;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReceiveResource extends Resource
{
    use ResourceTrait;

    protected static ?string $model = Receive::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $slug = '/charges/receive';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('Cobranças');
    }

    public static function getLabel(): ?string
    {
        return __('receita');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::generateForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::generateColumns())
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])->defaultSort('charge.due_date');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Modules\ReceiveResource\Pages\ListReceives::route('/'),
            'create' => Modules\ReceiveResource\Pages\CreateReceive::route('/create'),
            'edit' => Modules\ReceiveResource\Pages\EditReceive::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
