<?php

namespace App\Filament\Resources\Charge\ReceiveResource\Pages;

use App\Filament\Resources\Charge\ReceiveResource;
use App\Filament\Resources\Charge\Traits\ResourceTrait;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReceive extends EditRecord
{
    use ResourceTrait;

    protected static string $resource = ReceiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
