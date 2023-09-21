<?php

namespace App\Filament\Resources\Charge\Modules\ReceiveResource\Pages;

use App\Filament\Resources\Charge\Modules\ReceiveResource;
use App\Filament\Resources\Charge\Traits\ResourceTrait;
use App\Filament\Resources\Charge\Traits\CreateTrait;
use Filament\Resources\Pages\CreateRecord;

class CreateReceive extends CreateRecord
{
    use ResourceTrait, CreateTrait;

    protected static string $resource = ReceiveResource::class;
}
