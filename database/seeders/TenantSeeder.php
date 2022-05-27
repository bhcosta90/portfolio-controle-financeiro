<?php

namespace Database\Seeders;

use App\Models\Enums\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'name' => 'Usuário administrador',
            'email' => 'admin@example.com',
        ]);

        \App\Models\User::factory(7)->create([]);
    }
}
