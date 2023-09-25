<?php

namespace App\Filament\Resources\Charge\Modules\PaymentResource\Pages;

use App\Filament\Resources\Charge\Modules\PaymentResource;
use App\Filament\Resources\Charge\Traits\CreateTrait;
use App\Filament\Resources\Charge\Traits\ResourceTrait;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    use ResourceTrait, CreateTrait;

    protected static string $resource = PaymentResource::class;
}
