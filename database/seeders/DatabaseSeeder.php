<?php

namespace Database\Seeders;

use App\Models\Charge;
use App\Models\Recurrence;
use App\Models\Tenant;
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
        $tenant = Tenant::factory()->create(['id' => '60e34bf6-9e29-4e08-81f2-ab3848203e10']);

        \App\Models\User::factory(1)->create([
            'id' => 'dacaad78-b292-4f98-adf8-d8cd1a010003',
            'tenant_id' => $tenant->id,
            'email' => config('user.email'),
            'password' => config('user.password'),
        ]);

        $recurrence = Recurrence::factory()->create([
            'tenant_id' => $tenant->id,
            'days' => 30, 
            'name' => "Mensal"
        ]);

        \App\Models\Relationship::factory(5)->create([
            'tenant_id' => $tenant->id,
        ])->each(fn ($obj) => Charge::factory(rand(8, 15))->create([
            'tenant_id' => $obj->tenant_id,
            'relationship_type' => $obj->entity,
            'relationship_id' => $obj->id,
            'recurrence_id' => rand(0, 100) > 70 ? $recurrence->id : null,
        ]));

        \App\Models\AccountBank::factory(rand(3, 7))->create([
            'tenant_id' => $tenant->id,
        ]);
    }
}
