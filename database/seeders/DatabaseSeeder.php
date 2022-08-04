<?php

namespace Database\Seeders;

use Core\Application\BankAccount\Modules\Bank\Domain\BankEntity;
use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity;
use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity;
use Core\Application\Charge\Shared\Enums\ChargeTypeEnum;
use Core\Application\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (\App\Models\User::where('email', config('user.email'))->count() == 0) {
            $tenant = \App\Models\Tenant::factory()->create(['id' => '60e34bf6-9e29-4e08-81f2-ab3848203e10']);
            \App\Models\User::factory()->create([
                'id' => 'dacaad78-b292-4f98-adf8-d8cd1a010003',
                'tenant_id' => $tenant->id,
                'email' => config('user.email'),
                'password' => config('user.password'),
            ]);
        } else {
            $tenant = \App\Models\Tenant::factory()->create();
            $user = \App\Models\User::factory()->create([
                'tenant_id' => $tenant->id,
            ]);

            $this->command->line($user->email);
        }

        $recurrence = \App\Models\Recurrence::factory()->create([
            'tenant_id' => $tenant->id,
            'days' => 30,
            'name' => "Mensal"
        ]);

        $quantity = $this->command->ask('Enter quantity charges');

        \App\Models\Relationship::factory(rand(15, 45))->create([
            'tenant_id' => $tenant->id,
        ])->each(function ($obj) use ($recurrence, $quantity) {
            \App\Models\Charge::factory($quantity)->create([
                'tenant_id' => $obj->tenant_id,
                'entity' => match($obj->entity) {
                    CustomerEntity::class => ReceiveEntity::class,
                    CompanyEntity::class => PaymentEntity::class,
                    default => throw new Exception('Error - ' . $obj->entity)
                },
                'type' => match($obj->entity) {
                    CustomerEntity::class => ChargeTypeEnum::CREDIT,
                    CompanyEntity::class => ChargeTypeEnum::DEBIT,
                    default => throw new Exception('Error - ' . $obj->entity)
                },
                'relationship_type' => $obj->entity,
                'relationship_id' => $obj->id,
                'recurrence_id' => rand(0, 100) > 70 ? $recurrence->id : null,
            ]);

            \App\Models\Account::factory()->create([
                'tenant_id' => $obj->tenant_id,
                'entity_type' => $obj->entity,
                'entity_id' => $obj->id,
                'value' => 0,
            ]);
        });

        \App\Models\Bank::factory(rand(3, 7))->create([
            'tenant_id' => $tenant->id,
        ])->each(fn ($obj) => \App\Models\Account::factory()->create([
            'tenant_id' => $obj->tenant_id,
            'entity_type' => BankEntity::class,
            'entity_id' => $obj->id,
            'value' => 0,
        ]));
    }
}
