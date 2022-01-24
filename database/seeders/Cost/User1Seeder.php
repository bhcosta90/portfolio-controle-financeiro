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
                'resume' => 'Corte de unha do Bruno Henrique da Costa',
                'description' => 'Pagamento via PIX',
                'type' => 'month',
                'value' => 75,
                'due_date' => (new Carbon())->setDay('10')->format('Y-m-d'),
                'pay' => 1,
            ],
            [
                'customer_name' => 'André Trevisan',
                'resume' => 'Aula de violino',
                'value' => 120,
                "due_date" => (new Carbon)->setDay(10)->format('Y-m-d'),
                'type' => 'every_20th',
            ],
            [
                'customer_name' => 'CPFL',
                'resume' => 'Força',
                'value' => 150,
                "due_date" => (new Carbon)->setDay(10)->format('Y-m-d'),
                'type' => 'fifth_business_day',
            ],
            [
                'customer_name' => 'DAE',
                'resume' => 'Água',
                'value' => 50,
                "due_date" => (new Carbon)->setDay(10)->format('Y-m-d'),
                'type' => 'fifth_business_day',
            ],
            [
                'customer_name' => 'Rosângela Macário de Lima',
                'resume' => 'Televisão',
                'value' => 35 * 2,
                'type' => 'fifth_business_day',
                'due_date' => (new Carbon)->setDay(10)->format('Y-m-d'),
                'description' => 'Transferência via PIX: (19) 99181-7970 (Referente a televisão do Bruno, Paulo Afonso)',
            ],
            [
                'customer_name' => 'Faculdade Einstein Limeira',
                'resume' => 'Faculdade Mayara',
                'value' => 648,
                'type' => 'fifth_business_day',
                'due_date' => (new Carbon)->setDay(10)->format('Y-m-d'),
                'description' => 'Pagamento via boleto',
            ],
            [
                'customer_name' => 'Comgás',
                'resume' => 'Gás do apartamento',
                'value' => 5.00,
                'type' => 'fifth_business_day',
                'due_date' => (new Carbon)->setDay(10)->format('Y-m-d'),
                'description' => 'Pagamento via aplicativo',
            ],
            [
                'customer_name' => 'Caixa Economica Federal',
                'resume' => 'Apartamento',
                'value' => 595,
                'type' => 'fifth_business_day',
                'due_date' => (new Carbon)->setDay(10)->format('Y-m-d'),
                'description' => 'Pagamento do NuBank para a Caixa',
            ],
            [
                'customer_name' => 'Canção Nova',
                'resume' => 'Doação pela Josefina Costa',
                'value' => 50,
                'type' => 'fifth_business_day',
                'due_date' => (new Carbon)->setDay(10)->format('Y-m-d'),
                'description' => 'Pagamento via PIX',
            ],
            [
                'customer_name' => 'Josefina Costa',
                'resume' => 'Faxina',
                'value' => 150,
                'type' => 'fifth_business_day',
                'due_date' => (new Carbon)->setDay(10)->format('Y-m-d'),
            ],
            [
                'customer_name' => 'Josefina Costa',
                'resume' => 'Faculdade',
                'value' => 200,
                'type' => 'fifth_business_day',
                'due_date' => (new Carbon)->setDay(10)->format('Y-m-d'),
            ],
            [
                'customer_name' => 'Residencial Ágata',
                'resume' => 'Condomínio',
                'value' => 268,
                'type' => 'fifth_business_day',
                'due_date' => (new Carbon)->setDay(10)->format('Y-m-d'),
            ],
            [
                'customer_name' => 'Vivo',
                'resume' => 'Internet - Bruno Costa',
                'value' => 139.99,
                'type' => 'fifth_business_day',
                'due_date' => (new Carbon)->setDay(10)->format('Y-m-d'),
            ],
            [
                'customer_name' => 'Vivo',
                'resume' => 'Internet - José Costa',
                'value' => 119.99,
                'type' => 'fifth_business_day',
                'due_date' => (new Carbon)->setDay(10)->format('Y-m-d'),
            ],
        ];
    }

    protected function service()
    {
        return Service::class;
    }
}
