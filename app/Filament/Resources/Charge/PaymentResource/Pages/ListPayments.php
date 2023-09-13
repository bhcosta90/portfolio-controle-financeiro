<?php

namespace App\Filament\Resources\Charge\PaymentResource\Pages;

use App\Filament\Resources\Charge\PaymentResource;
use App\Filament\Resources\Charge\Traits\ListTrait;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPayments extends ListRecords
{
    use ListTrait;

    protected static string $view = 'filament.resources.charge.pages.list-charges';

    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
