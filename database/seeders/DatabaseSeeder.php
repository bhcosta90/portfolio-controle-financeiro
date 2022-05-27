<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Costa\Modules\Account\Entities\AccountEntity;
use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Shareds\ValueObjects\ModelObject;
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
        \App\Models\User::factory()->create([
            'tenant_id' => $tenant = Tenant::factory()->create(),
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        \App\Models\User::factory(10)->create([
            'tenant_id' => $tenant->id,
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
    }
}
