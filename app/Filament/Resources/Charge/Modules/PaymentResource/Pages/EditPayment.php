<?php

namespace App\Filament\Resources\Charge\Modules\PaymentResource\Pages;

use App\Filament\Resources\Charge\Modules\PaymentResource;
use App\Filament\Resources\Charge\Traits\EditTrait;
use App\Filament\Resources\Charge\Traits\ResourceTrait;
use App\Models\Charge\Charge;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPayment extends EditRecord
{
    use ResourceTrait, EditTrait;

    protected static string $resource = PaymentResource::class;
    public Charge|Model|int|string|null $record;
}
