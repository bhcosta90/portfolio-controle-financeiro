<?php

namespace App\Filament\Resources\Charge\ReceiveResource\Pages;

use App\Filament\Resources\Charge\ReceiveResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReceives extends ListRecords
{
    protected static string $resource = ReceiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
