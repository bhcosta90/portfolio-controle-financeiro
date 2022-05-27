<?php

namespace Database\Seeders;

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
        if (tenant()->id == '0b26c3fd-bb33-4419-867a-5aee383353f5') {
            \App\Models\User::factory()->create([
                'name' => 'Bruno Henrique da Costa',
                'email' => 'bhcosta90@gmail.com',
                'password' => '$2y$10$A5txKXUODkQyLrTOdOA7g.fzu/xY5lbHtp/MkCdPX7wwKcJ9h1LFu',
            ]);
        } else {
            \App\Models\User::factory()->create([
                'name' => 'Usuário administrador',
                'email' => 'test@example.com',
            ]);
        }

        \App\Models\User::factory(9)->create([]);
    }
}
