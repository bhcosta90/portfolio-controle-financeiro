<?php

namespace App\Filament\Resources\Charge\PaymentResource\Pages;

use App\Filament\Resources\Charge\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;
}
