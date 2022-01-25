<?php

namespace Database\Seeders\Income;

use App\Services\IncomeService as Service;
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
                'customer_name' => 'Jair da Costa',
                'resume' => 'Mensalidade do celular',
                'parcel_total' => 10,
                'value' => 799,
                'due_date' => '2021-08-05'
            ],
            [
                'customer_name' => 'Jair da Costa',
                'resume' => 'Mensalidade do Seguro para o celular',
                'parcel_total' => 12,
                'value' => 235.92,
                'due_date' => '2021-08-05'
            ],
            [
                'customer_name' => 'José Maria da Costa',
                'resume' => 'Empréstimo do cartão de crédito',
                'value' => 573.62,
                'due_date' => '2021-02-05',
                'description' => 'Com desconto das duas parcelas do armário de quarto (Valor do Cartão = 1065 | Valor da Faculdade = 308.62 | Parcela do armário = -800)',
            ],
            [
                'customer_name' => 'José Maria da Costa',
                'resume' => 'Empréstimo para funerária',
                'value' => 1750.00,
                'due_date' => '2021-02-05',
            ],
            [
                'customer_name' => 'PJBank',
                'resume' => 'Vale do pagamento',
                'value' => 1893.27,
                'due_date' => (new Carbon())->setDay('01')->format('Y-m-d'),
                'type' => 'every_20th',
            ],
            [
                'customer_name' => 'PJBank',
                'resume' => 'Pagamento',
                'value' => 2628.81,
                'due_date' => (new Carbon())->setDay('01')->format('Y-m-d'),
                'type' => 'fifth_business_day',
            ],
            [
                "customer_name" => "Victória Costa",
                'resume' => 'Diaria da cuidadora',
                "value" => 100,
                "due_date" => "2021-12-28",
            ],
            [
                "customer_name" => "Victória Costa",
                'resume' => 'Fogão',
                "value" => 150,
                "due_date" => "2021-12-28",
            ],
            [
                'customer_name' => 'Paulo Afonso',
                'resume' => 'Televisão',
                'value' => 35,
                'tipo' => 'fifth_business_day',
                'due_date' => '2021-10-01',
            ],
            [
                'customer_name' => 'José Costa',
                'resume' => 'Internet descontando o netflix',
                'value' => 97.05,
                'tipo' => 'fifth_business_day',
                'due_date' => (new Carbon())->setDay('01')->format('Y-m-d'),
            ]

        ];
    }

    protected function service()
    {
        return Service::class;
    }
}
