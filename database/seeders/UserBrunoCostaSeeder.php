<?php

namespace Database\Seeders;

use App\Models\Cost;
use App\Models\Income;
use App\Models\Parcel;
use Carbon\Carbon;
use Costa\LaravelPackage\Utils\Value;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserBrunoCostaSeeder extends Seeder
{
    public function run()
    {
        if (\App\Models\User::where('email', 'bhcosta90@gmail.com')->count() == 0) {
            $user = \App\Models\User::factory()->create([
                'name' => 'Bruno Henrique da Costa',
                'email' => 'bhcosta90@gmail.com',
                'password' => '$2y$10$bT3qC8sgViO1iAuul0vmce90YLYHbNeRfcKdZLV.Xzzgu3cqacBV.',
            ]);

            $recurrencies = DB::table('recurrencies')
                ->where('user_id', $user->id)
                ->get()
                ->pluck('id', 'type')
                ->toArray();

            foreach ($this->costs() as $rs) {
                $rs['recurrency_id'] = $recurrencies[$rs['type'] ?? null] ?? null;
                $rs['user_id'] = $user->id;
                $rs['value_recurrency'] = $rs['value'];

                $objCost = Cost::create([]);
                unset($rs['type']);
                $this->register($objCost, $rs);
            }

            foreach ($this->income() as $rs) {
                $rs['recurrency_id'] = $recurrencies[$rs['type'] ?? null] ?? null;
                $rs['user_id'] = $user->id;
                $rs['value_recurrency'] = $rs['value'];

                $objIncome = Income::create([]);
                unset($rs['type']);
                $this->register($objIncome, $rs);
            }

            DB::table('charges')->where('parcel_actual', '<=', 6)
                ->where('customer_name', 'Jair da Costa')
                ->update(['deleted_at' => Carbon::now()]);
        }
    }

    private function register($obj, $data){
        $data['date_start'] = $data['due_date'];
        $data['date_end'] = $data['due_date'];

        if (!empty($data['parcel_total'])) {
            $parcels = collect(app(Value::class)->parcel(new Carbon($data['due_date']), $data['value'], $data['parcel_total']));

            $data['date_start'] = $data['due_date'];
            $data['date_end'] = $parcels->last()['due_date'];
        }

        $obj->charge()->create($data + [
            'basecharge_type' => get_class($obj),
            'basecharge_id' => $obj->id,
        ]);

        if (isset($parcels)) {
            foreach($parcels as $i => $parcel) {
                $objParcel = Parcel::create();

                $objParcel->charge()->create([
                    'chargeable_type' => get_class($objParcel),
                    'user_id' => $data['user_id'],
                    'resume' => 'Parcel :actual',
                    'parcel_actual' => $i + 1,
                    'parcel_total' => count($parcel) + 1,
                    'value' => $parcel['value'],
                    'value_recurrency' => $parcel['value'],
                    'customer_name' => $data['customer_name'],
                    'due_date' => $parcel['due_date'],
                    'basecharge_type' => get_class($obj),
                    'basecharge_id' => $obj->id,
                ]);
            }
        }
    }

    private function income()
    {
        return [
            [
                'customer_name' => 'Jair da Costa',
                'resume' => 'Mensalidade do celular',
                'parcel_total' => 10,
                'value' => 799,
                'due_date' => '2021-08-20'
            ],
            [
                'customer_name' => 'Jair da Costa',
                'resume' => 'Mensalidade do Seguro para o celular',
                'parcel_total' => 12,
                'value' => 235.92,
                'due_date' => '2021-08-20'
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
                'type' => 'fifth_business_day',
                'due_date' => '2021-10-01',
            ],
            [
                'customer_name' => 'José Costa',
                'resume' => 'Internet descontando o netflix',
                'value' => 97.05,
                'type' => 'fifth_business_day',
                'due_date' => (new Carbon())->setDay('01')->format('Y-m-d'),
            ]

        ];
    }

    private function costs()
    {
        return [
            [
                'customer_name' => 'Podóloga',
                'resume' => 'Corte de unha do Bruno Henrique da Costa',
                'description' => 'Pagamento via PIX',
                'type' => 'month',
                'value' => 80,
                'due_date' => (new Carbon())->setDay('10')->format('Y-m-d'),
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
                'resume' => 'Ýgua',
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
            [
                'customer_name' => 'Nubank',
                'resume' => 'Cartão de crédito',
                'value' => 1800,
                'type' => 'every_20th',
                'due_date' => (new Carbon())->setDay('01')->format('Y-m-d'),
            ]
        ];
    }
}
