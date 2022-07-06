<?php

namespace Database\Seeders;

use App\Models\Tenant;
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
        $tenant = Tenant::factory()->create(['id' => '60e34bf6-9e29-4e08-81f2-ab3848203e10']);

        \App\Models\User::factory(1)->create([
            'id' => 'dacaad78-b292-4f98-adf8-d8cd1a010003',
            'tenant_id' => $tenant->id,
            'email' => config('user.email'),
            'password' => config('user.password'),
        ]);
    }
}
