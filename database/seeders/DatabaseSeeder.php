<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::reguard();
        \App\Models\User::factory(10)->create()->each(fn($obj) => $obj->default());

        $tenant = Tenant::create([
            'id' => 'testecontato',
        ]);

        \App\Models\User::factory()->create([
            'email' => 'contato@noreply.com',
            'tenant_id' => $tenant->id,
            'password' => '$2y$10$gaWJdbSSzQY4bF76qRp6auMh0Sy1N6Qq7TuMo9eLSewCXKDf34r9C'
        ]);

        $this->call(ContatoSeeder::class);

        dump(\App\Models\User::orderBy('id')->pluck('tenant_id', 'email')->toArray());
    }
}
