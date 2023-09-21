<?php

namespace App\Filament\Resources\Charge;

use App\Filament\Resources\Charge\ChargeResource\Widgets\LastWidget;
use App\Filament\Resources\Charge\PaymentResource\Pages;
use App\Filament\Resources\Charge\PaymentResource\RelationManagers;
use App\Filament\Resources\Charge\Traits\ResourceTrait;
use App\Models\Charge\Payment;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    use ResourceTrait;

    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    public static function getLabel(): ?string
    {
        return __('despesa');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Cobranças');
    }

    public static function getWidgets(): array
    {
        return [
            LastWidget::class,
        ];
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
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
