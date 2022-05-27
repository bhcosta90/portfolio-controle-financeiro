<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\Charge;
use App\Models\Tenant;
use Costa\Modules\Account\Entities\AccountEntity;
use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Account\UseCases\Bank\BankCreateUseCase;
use Costa\Modules\Charge\Shareds\Enums\Type;
use Costa\Modules\Charge\UseCases\Payment\PaymentCreatedUseCase;
use Costa\Modules\Charge\UseCases\Charge\DTO\Create\Input as CreateInput;
use Costa\Modules\Charge\UseCases\Receive\ReceiveCreatedUseCase;
use Costa\Modules\Relationship\Entities\CustomerEntity;
use Costa\Modules\Relationship\Entities\SupplierEntity;
use Costa\Modules\Relationship\UseCases\Customer\CustomerCreateUseCase;
use Costa\Modules\Relationship\UseCases\Supplier\SupplierCreateUseCase;
use Costa\Shareds\ValueObjects\ModelObject;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class CostaSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::reguard();

        $tenant = Tenant::factory()->create();
        
        $user = \App\Models\User::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Bruno Henrique da Costa',
            'email' => 'bhcosta90@gmail.com',
            'password' => '$2y$10$A5txKXUODkQyLrTOdOA7g.fzu/xY5lbHtp/MkCdPX7wwKcJ9h1LFu',
        ]);

        /** @var AccountRepositoryInterface */
        $account = app(AccountRepositoryInterface::class);

        $account->insert(
            new AccountEntity(
                model: new ModelObject(id: $tenant->uuid, type: $tenant),
                value: 0,
                increment: $tenant->id
            )
        );

        Auth::login($user);

        $idMensal = \App\Models\Recurrence::factory()->create(['name' => 'Mensal', 'days' => 30])->uuid;

        app(BankCreateUseCase::class)->exec(new \Costa\Modules\Account\UseCases\Bank\DTO\Create\Input(
            name: 'PicPay',
            value: 942.71,
            active: true,
        ));

        app(BankCreateUseCase::class)->exec(new \Costa\Modules\Account\UseCases\Bank\DTO\Create\Input(
            name: '99Pay',
            value: 497,
            active: false,
        ));

        app(BankCreateUseCase::class)->exec(new \Costa\Modules\Account\UseCases\Bank\DTO\Create\Input(
            name: 'Flash Tecnologias',
            value: 0,
            active: false,
        ));

        app(BankCreateUseCase::class)->exec(new \Costa\Modules\Account\UseCases\Bank\DTO\Create\Input(
            name: 'Nu Pagamentos S.A.',
            value: 0,
            active: false,
        ));

        $data = [
            [
                'name' => 'Caixa Economica Federal',
                'type_name' => SupplierEntity::class,
                'type_charge' => Type::CREDIT,
                'value' => 595,
                'recurrence_id' => $idMensal,
                'title' => 'Apartamento',
                'date_due' => '05-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargePaymentController',
            ],
            [
                'name' => 'NuBank',
                'type_name' => SupplierEntity::class,
                'type_charge' => Type::CREDIT,
                'value' => 1800,
                'recurrence_id' => $idMensal,
                'title' => 'Cartão de Crédito',
                'date_due' => '05-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargePaymentController',
            ],
            [
                'name' => 'C6 Bank',
                'type_name' => SupplierEntity::class,
                'type_charge' => Type::CREDIT,
                'value' => 1300,
                'recurrence_id' => $idMensal,
                'title' => 'Cartão de Crédito',
                'date_due' => '05-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargePaymentController',
            ],
            [
                'name' => 'Canção Nova',
                'type_name' => SupplierEntity::class,
                'type_charge' => Type::CREDIT,
                'value' => 25,
                'recurrence_id' => $idMensal,
                'title' => 'Doação pela Josefina Costa',
                'date_due' => '05-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargePaymentController',
            ],
            [
                'name' => 'DAE',
                'type_name' => SupplierEntity::class,
                'type_charge' => Type::CREDIT,
                'value' => 50,
                'recurrence_id' => $idMensal,
                'title' => 'Água',
                'date_due' => '05-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargePaymentController',
            ],
            [
                'name' => 'André Trevisan',
                'type_name' => SupplierEntity::class,
                'type_charge' => Type::CREDIT,
                'value' => 120,
                'recurrence_id' => $idMensal,
                'title' => 'Violino',
                'date_due' => '05-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargePaymentController',
            ],
            [
                'name' => 'CPFL',
                'type_name' => SupplierEntity::class,
                'type_charge' => Type::CREDIT,
                'value' => 150,
                'recurrence_id' => $idMensal,
                'title' => 'Força',
                'date_due' => '05-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargePaymentController',
            ],
            [
                'name' => 'Vivo',
                'type_name' => SupplierEntity::class,
                'type_charge' => Type::CREDIT,
                'value' => 129.99,
                'recurrence_id' => $idMensal,
                'title' => 'Internet - José Maria da Costa',
                'date_due' => '05-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargePaymentController',
            ],
            [
                'name' => 'Vivo',
                'type_name' => SupplierEntity::class,
                'type_charge' => Type::CREDIT,
                'value' => 149.99,
                'recurrence_id' => $idMensal,
                'title' => 'Internet - Bruno Costa',
                'date_due' => '05-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargePaymentController',
            ],
            [
                'name' => 'Faculdade Einstein Limeira',
                'type_name' => SupplierEntity::class,
                'type_charge' => Type::CREDIT,
                'value' => 648,
                'recurrence_id' => $idMensal,
                'title' => 'Faculdade Mayara',
                'date_due' => '05-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargePaymentController',
            ],
            [
                'name' => 'Comgás',
                'type_name' => SupplierEntity::class,
                'type_charge' => Type::CREDIT,
                'value' => 6,
                'recurrence_id' => $idMensal,
                'title' => 'Gás do apartamento	',
                'date_due' => '05-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargePaymentController',
            ],
            [
                'name' => 'Podóloga',
                'type_name' => SupplierEntity::class,
                'type_charge' => Type::CREDIT,
                'value' => 80,
                'recurrence_id' => $idMensal,
                'title' => 'Bruno Henrique da Costa',
                'date_due' => '05-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargePaymentController',
            ],
            [
                'name' => 'Rosângela Macário de Lima',
                'type_name' => SupplierEntity::class,
                'type_charge' => Type::CREDIT,
                'value' => 70,
                'recurrence_id' => $idMensal,
                'title' => 'Televisão',
                'date_due' => '05-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargePaymentController',
            ],
            [
                'name' => 'PJBank',
                'type_name' => CustomerEntity::class,
                'type_charge' => Type::DEBIT,
                'value' => 100,
                'recurrence_id' => $idMensal,
                'title' => 'Alimentação',
                'date_due' => '05-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargeReceiveController',
            ],
            [
                'name' => 'Paulo Afonso',
                'type_name' => CustomerEntity::class,
                'type_charge' => Type::DEBIT,
                'value' => 35,
                'recurrence_id' => $idMensal,
                'title' => 'Televisão',
                'date_due' => '05-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargeReceiveController',
            ],
            [
                'name' => 'José Maria da Costa',
                'type_name' => CustomerEntity::class,
                'type_charge' => Type::DEBIT,
                'value' => 39.32,
                'title' => 'Mensalidade do Seguro para o celular',
                'date_due' => '05-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargeReceiveController',
                'parcel' => 2
            ],
            [
                'name' => 'José Maria da Costa',
                'type_name' => CustomerEntity::class,
                'type_charge' => Type::DEBIT,
                'value' => 25,
                'recurrence_id' => $idMensal,
                'title' => 'Doação da canção nova (Josefina Costa)',
                'date_due' => '05-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargeReceiveController',
            ],
            [
                'name' => 'José Maria da Costa',
                'type_name' => CustomerEntity::class,
                'type_charge' => Type::DEBIT,
                'value' => 107.05,
                'recurrence_id' => $idMensal,
                'title' => 'Internet descontando o netflix',
                'date_due' => '05-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargeReceiveController',
            ],
            [
                'name' => 'José Maria da Costa',
                'type_name' => CustomerEntity::class,
                'type_charge' => Type::DEBIT,
                'value' => 1750.00,
                'title' => 'Empréstimo para funerária',
                'date_due' => '05-01-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargeReceiveController',
            ],
            [
                'name' => 'José Maria da Costa',
                'type_name' => CustomerEntity::class,
                'type_charge' => Type::DEBIT,
                'value' => 573.62,
                'title' => 'Empréstimo do cartão de crédito',
                'description' => 'Com desconto referente a parcela do armário de quarto (Valor do Cartão = 1065 | Valor da Faculdade = 308.62 | Parcela do armário = -800)',
                'date_due' => '05-01-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargeReceiveController',
            ],
            [
                'name' => 'José Maria da Costa',
                'type_name' => CustomerEntity::class,
                'type_charge' => Type::DEBIT,
                'value' => 2400.00,
                'value_pay' => 1120,
                'title' => 'Cerimonialista',
                'date_due' => '05-01-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargeReceiveController',
            ],
            [
                'name' => 'PJBank',
                'type_name' => CustomerEntity::class,
                'type_charge' => Type::DEBIT,
                'value' => 1000,
                'recurrence_id' => $idMensal,
                'title' => 'Pagamento',
                'date_due' => '10-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargeReceiveController',
            ],
            [
                'name' => 'PJBank',
                'type_name' => CustomerEntity::class,
                'type_charge' => Type::DEBIT,
                'value' => 1893.27,
                'recurrence_id' => $idMensal,
                'title' => 'Vale',
                'date_due' => '20-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargeReceiveController',
            ],
            [
                'name' => 'PJBank',
                'type_name' => CustomerEntity::class,
                'type_charge' => Type::DEBIT,
                'value' => 2600,
                'recurrence_id' => $idMensal,
                'title' => 'Primeira parcela do 13 pagamento',
                'date_due' => '30-06-2022',
                'charge' => 'App\\Http\\Controllers\\Admin\\Charge\\ChargeReceiveController',
            ],
        ];

        $dataRelationship = [];

        foreach ($data as $rs) {
            $key = md5($rs['type_name'] . $rs['name']);

            if (empty($dataRelationship[$key])) {
                if ($rs['type_name'] == SupplierEntity::class) {
                    $dataRelationship[$key] = app(SupplierCreateUseCase::class)->exec(
                        new \Costa\Modules\Relationship\UseCases\Supplier\DTO\Create\Input(
                            name: $rs['name']
                        )
                    )->id;
                } else {
                    $dataRelationship[$key] = app(CustomerCreateUseCase::class)->exec(
                        new \Costa\Modules\Relationship\UseCases\Customer\DTO\Create\Input(
                            name: $rs['name']
                        )
                    )->id;
                }
            }

            $input = new CreateInput(
                title: $rs['title'],
                description: $rs['description'] ?? null,
                value: $rs['value'],
                relationship: new ModelObject(id: $dataRelationship[$key], type: $rs['type_name']),
                date: new DateTime($rs['date_due']),
                parcel: $rs['parcel'] ?? 1,
                recurrence: $rs['recurrence_id'] ?? null,
            );

            $ret = app(
                $rs['type_charge'] == Type::CREDIT
                    ? PaymentCreatedUseCase::class
                    : ReceiveCreatedUseCase::class
            )->exec($input);

            if($rs['value_pay'] ?? null){
                $objCharge = Charge::where('uuid', $ret->charges[0]->id)->first();
                $objCharge->value_pay = $rs['value_pay'];
                $objCharge->save();
            }            
        }
    }
}
