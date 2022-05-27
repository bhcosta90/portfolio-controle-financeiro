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

class CostaMigrateSeeder extends Seeder
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
                'value' => 150,
                'recurrence_id' => $idMensal,
                'title' => 'Teste',
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
