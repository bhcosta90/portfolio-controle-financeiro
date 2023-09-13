<?php

namespace App\Filament\Resources\Charge\PaymentResource\Pages;

use App\Filament\Resources\Charge\PaymentResource;
use App\Filament\Resources\Charge\Traits\ResourceTrait;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    use ResourceTrait;

    protected static string $resource = PaymentResource::class;
}
