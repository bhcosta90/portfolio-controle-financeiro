<?php

namespace Database\Seeders;

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
        $user = \App\Models\User::factory()->create([
            'username' => 'bhcosta90',
            'name' => 'Bruno Costa',
            'email' => 'bhcosta90@gmail.com'
        ]);

        \App\Models\Account::factory()->create([

        ]);
    }
}
