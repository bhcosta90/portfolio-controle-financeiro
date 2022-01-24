<?php

namespace Database\Seeders\Cost;

use App\Services\CostService as Service;
use Carbon\Carbon;
use Database\Seeders\Traits\ChargeTrait;
use Illuminate\Database\Seeder;

class User1Seeder extends Seeder
{
    use ChargeTrait;

    protected function data()
    {
        return [
            [
                'customer_name' => 'Podóloga',
                'resume' => 'Corte de unha do Bruno',
                'description' => 'Pagamento via PIX',
                'type' => 'month',
                'value' => 75,
                'due_date' => (new Carbon())->setDay('10')->format('Y-m-d'),
                'pay' => 1,
            ]
        ];
    }

    protected function service()
    {
        return Service::class;
    }
}
