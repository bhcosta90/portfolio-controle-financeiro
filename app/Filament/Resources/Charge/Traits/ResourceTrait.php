<?php

namespace App\Filament\Resources\Charge\Traits;

use App\Models\Account;
use App\Models\Category;
use App\Models\Enum\Charge\ParcelEnum;
use App\Models\Enum\Charge\TypeEnum;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait ResourceTrait
{

    public static function resolveRecordRouteBinding(int|string $key): ?Model
    {
        return app(static::getModel())
            ->resolveRouteBindingQuery(static::getEloquentQuery(), $key, static::getRecordRouteKeyName())
            ->first()->charge;
    }

    protected static function generateForm(): array
    {
        return [
            Forms\Components\TextInput::make('description')
                ->hiddenLabel()
                ->required(fn(string $context) => $context === 'edit')
                ->placeholder(__('Descrição')),
            Forms\Components\Select::make('type')
                ->live()
                ->hiddenLabel()
                ->searchable()
                ->required()
                ->options([
                    TypeEnum::UNIQUE->value => TypeEnum::UNIQUE->getName(),
                    TypeEnum::PARCEL->value => TypeEnum::PARCEL->getName(),
                    TypeEnum::MONTHLY->value => TypeEnum::MONTHLY->getName(),
                ])->default(TypeEnum::UNIQUE->value),
            Forms\Components\Group::make([
                Forms\Components\TextInput::make('value')
                    ->label(__('Valor'))
                    ->required()
                    ->live()
                    ->placeholder(__('Valor')),
                Forms\Components\DatePicker::make('due_date')
                    ->label(__('Data de vencimento'))
                    ->required()
                    ->default(now()),
                Forms\Components\Select::make('account_id')
                    ->required()
                    ->label(__('Conta'))
                    ->selectablePlaceholder(false)
                    ->searchable()
                    ->options($accounts = Account::pluck())
                    ->default(array_keys($accounts)[0]),
            ])->columns(3)
                ->columnSpanFull(),
            Forms\Components\Fieldset::make()->schema([
                Forms\Components\Select::make('parcel_type')
                    ->live()
                    ->options([
                        ParcelEnum::TOTAL->value => ParcelEnum::TOTAL->getName(),
                        ParcelEnum::MONTH->value => ParcelEnum::MONTH->getName(),
                    ])
                    ->label(__('Tipo da parcela'))
                    ->columnSpan(1)
                    ->default(ParcelEnum::TOTAL->value)
                    ->selectablePlaceholder(false),
                Forms\Components\TextInput::make('parcel_quantity')
                    ->numeric()
                    ->live()
                    ->rules(['min:1'])
                    ->default(1)
                    ->minValue(1)
                    ->extraInputAttributes([
                        'step' => 1
                    ])
                    ->label(__('Quantidade de parcela')),
                Forms\Components\Placeholder::make('parcel')
                    ->view('filament.forms.component.charge.parcel')
                    ->columnSpanFull(),
            ])->hidden(fn(Forms\Get $get): bool => $get('type') != TypeEnum::PARCEL->value),
            Forms\Components\Select::make('category_id')
                ->label(__('Categoria'))
                ->placeholder(__('Outros'))
                ->live()
                ->options(Category::pluck())
                /*->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->label(__('Nome'))
                        ->required()
                        ->rules(['min:3', 'max:100']),
                ])
                ->createOptionUsing(function($data){
                    $category = Category::create($data);
                    return $category->id;
                })*/
                ->columnSpan(fn(Forms\Get $get): int => $get('category_id') ? 1 : 2),
            Forms\Components\Select::make('sub_category_id')
                ->label(__('Sub categoria'))
                ->placeholder(__('Outros'))
                ->options(fn(Forms\Get $get) => Category::pluck($get('category_id')))
                ->hidden(fn(Forms\Get $get): bool => !$get('category_id')),
            Forms\Components\Textarea::make('note')
                ->rows(7)
                ->label(__('Observação'))
                ->columnSpanFull(),
        ];
    }

    protected static function generateColumns(): array
    {
        return [
            Tables\Columns\IconColumn::make('charge.is_payed')
                ->label(__('Pago'))
                ->action(fn($record) => DB::transaction(fn() => $record->charge->payed(!$record->charge->is_payed)))
                ->boolean()
                ->extraCellAttributes([
                    'class' => 'w-0'
                ]),
            Tables\Columns\TextColumn::make('charge.description')
                ->label(__('Descrição')),

            Tables\Columns\TextColumn::make('charge.value')
                ->label(__('Valor'))
                ->money(config('money.defaults.currency')),

            Tables\Columns\TextColumn::make('charge.due_date')
                ->dateTime('d/m/Y')
                ->sortable()
                ->label(__('Vencimento')),

            Tables\Columns\TextColumn::make('charge.account.name')
                ->label(__('Conta')),
        ];
    }

    public function value(): float
    {
        $value = $this->data['value'] ?: 0;

        if ($this->data['parcel_type'] == ParcelEnum::TOTAL->value) {
            $quantity = (int)$this->data['parcel_quantity'];
            if ($quantity <= 0) {
                $quantity = 1;
            }
            $value = $value / $quantity;
        }

        return $value;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $modelClass = app($this->getModel());
            $model = $modelClass->create([]);
            $model->charge()->create($data);
            return $model;
        });
    }
}
