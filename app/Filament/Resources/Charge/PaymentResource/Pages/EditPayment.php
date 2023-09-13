<?php

namespace App\Filament\Resources\Charge\PaymentResource\Pages;

use App\Filament\Resources\Charge\PaymentResource;
use App\Filament\Resources\Charge\Traits\ResourceTrait;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPayment extends EditRecord
{
    use ResourceTrait;

    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
