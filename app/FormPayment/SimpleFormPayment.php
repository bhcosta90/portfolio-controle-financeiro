<?php

namespace App\FormPayment;

use Illuminate\Support\Facades\Log;

final class SimpleFormPayment implements FormPaymentContract
{
    public function syncFormPayment(array $data)
    {
        Log::info($data);
    }
}
