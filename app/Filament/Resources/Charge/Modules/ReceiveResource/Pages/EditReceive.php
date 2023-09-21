<?php

namespace App\Filament\Resources\Charge\Modules\ReceiveResource\Pages;

use App\Filament\Resources\Charge\Modules\ReceiveResource;
use App\Filament\Resources\Charge\Traits\EditTrait;
use App\Filament\Resources\Charge\Traits\ResourceTrait;
use App\Models\Charge\Charge;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditReceive extends EditRecord
{
    use ResourceTrait, EditTrait;

    protected static string $resource = ReceiveResource::class;
    public Charge|Model|int|string|null $record;
}
