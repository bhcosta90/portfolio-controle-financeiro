<?php

namespace App\Filament\Resources\Charge\ReceiveResource\Pages;

use App\Filament\Resources\Charge\ReceiveResource;
use App\Filament\Resources\Charge\Traits\ListTrait;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReceives extends ListRecords
{
    use ListTrait;

    protected static string $view = 'filament.resources.charge.pages.list-charges';

    protected static string $resource = ReceiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
