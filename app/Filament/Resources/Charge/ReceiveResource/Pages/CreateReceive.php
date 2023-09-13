<?php

namespace App\Filament\Resources\Charge\ReceiveResource\Pages;

use App\Filament\Resources\Charge\ReceiveResource;
use App\Filament\Resources\Charge\Traits\ResourceTrait;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReceive extends CreateRecord
{
    use ResourceTrait;

    protected static string $resource = ReceiveResource::class;
}
