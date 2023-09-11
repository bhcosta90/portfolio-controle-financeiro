<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tenant = Tenant::create([
            'id' => 'a23ded4d-d0d8-4736-88ba-22aadc588f02',
            'tenancy_db_name' => env('DB_DATABASE'),
            'tenancy_db_host' => env('DB_HOST'),
            'tenancy_db_username' => env('DB_USERNAME'),
        ]);

        $tenant->domains()->create([
            'domain' => 'demo'
        ]);

        tenancy()->initialize($tenant);

        User::factory()->create([
            'id' => '220ffc42-aa07-4f00-822c-f1cf8a2c0dd3',
            'name' => 'Bruno Henrique da Costa',
            'email' => 'bhcosta90@gmail.com',
            'password' => '$2y$10$hoTymm/4dqKbYRCNWx7TKuAMOHmh/Som9xZvhpLuVMQmzcbHsg9OC'
        ]);
    }
}
