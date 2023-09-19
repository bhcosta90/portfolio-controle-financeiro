<?php

namespace App\Filament\Resources\Charge\PaymentResource\Pages;

use App\Filament\Resources\Charge\PaymentResource;
use App\Filament\Resources\Charge\Traits\EditTrait;
use App\Filament\Resources\Charge\Traits\ResourceTrait;
use App\Models\Charge\Charge;
use App\Models\Enum\Charge\TypeEnum;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPayment extends EditRecord
{
    use ResourceTrait, EditTrait;

    public Charge | Model | int | string | null $record;

    protected static string $resource = PaymentResource::class;
}
