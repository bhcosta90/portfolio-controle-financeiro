<?php

namespace Database\Seeders;

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
        \App\Models\Tenant::factory()->create(['id' => $tenant = 'c606b480-a559-48f8-9737-0b896442ab25']);
        \App\Models\User::factory()->create([
            'email' => config('user.user'), 
            'password' => config('user.pass'), 
            'tenant_id' => $tenant
        ]);
    }
}
