<?php

namespace App\Filament\Resources\Charge\ReceiveResource\Pages;

use App\Filament\Resources\Charge\ReceiveResource;
use App\Filament\Resources\Charge\Traits\EditTrait;
use App\Filament\Resources\Charge\Traits\ResourceTrait;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReceive extends EditRecord
{
    use ResourceTrait, EditTrait;

    protected static string $resource = ReceiveResource::class;
}
