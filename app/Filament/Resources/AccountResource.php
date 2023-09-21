<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Filament\Resources\AccountResource\RelationManagers;
use App\Models\Account;
use App\Models\Enum\Account\CategoryEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): ?string
    {
        return __('Cadastros');
    }


    public static function getLabel(): ?string
    {
        return __('conta');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Nome'))
                    ->required()
                    ->rules([
                        'min:3',
                        'max:100',
                    ]),
                Forms\Components\Select::make('type')
                    ->label(__('Categoria'))
                    ->placeholder(__('Outros'))
                    ->searchable()
                    ->options([
                        CategoryEnum::MY_WALLET->value => CategoryEnum::MY_WALLET->getName(),
                        CategoryEnum::CURRENT_ACCOUNT->value => CategoryEnum::CURRENT_ACCOUNT->getName(),
                        CategoryEnum::SAVINGS_ACCOUNT->value => CategoryEnum::SAVINGS_ACCOUNT->getName(),
                    ]),
                Forms\Components\Fieldset::make('values')
                    ->hiddenLabel()
                    ->hidden($form->model->id ?? false)
                    ->schema([
                        Forms\Components\TextInput::make('balance')
                            ->label(__('Saldo inicial'))
                            ->default(0)
                            ->numeric(),
                        Forms\Components\TextInput::make('overdraft')
                            ->label(__('Cheque especial'))
                            ->default(0)
                            ->numeric(),
                    ]),
                Forms\Components\Textarea::make('note')
                    ->rows(5)
                    ->label(__('Dados adicionais'))
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->label(__('Nome')),
            ])
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
            ])->defaultSort('name')->persistSortInSession();
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
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
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
