<?php

namespace App\Filament\Resources\Charge\ReceiveResource\Pages;

use App\Filament\Resources\Charge\ReceiveResource;
use App\Filament\Resources\Charge\Traits\ChargeTrait;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReceive extends CreateRecord
{
    use ChargeTrait;

    protected static string $resource = ReceiveResource::class;
}
