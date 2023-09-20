<?php

namespace App\Filament\Resources\Charge\PaymentResource\Pages;

use App\Filament\Resources\Charge\PaymentResource;
use App\Filament\Resources\Charge\Traits\ResourceTrait;
use App\Filament\Resources\Charge\Traits\SaveTrait;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    use ResourceTrait, SaveTrait;

    protected static string $resource = PaymentResource::class;
}
