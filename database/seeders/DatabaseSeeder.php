<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (!app()->isProduction()) {
            $this->register('0b26c3fd-bb33-4419-867a-5aee383353f5', env('DB_DATABASE') . '_1', 'localhost');
            $this->register('62a91d51-e600-404d-b88d-a696d6e0b693', env('DB_DATABASE') . '_2', 'localhost1');
            Artisan::call('tenants:migrate-fresh');
            Artisan::call('tenants:seed', [
                '--class' => TenantSeeder::class,
            ]);
        }
    }

    private function register($id, $database, $url)
    {
        DB::table('tenants')->insert([
            'id' => $id,
            'data' => json_encode(['tenancy_db_name' => $database]),
            'created_at' => $date = Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => $date,
        ]);

        DB::table('domains')->insert([
            'domain' => $url . '.localhost',
            'tenant_id' => $id,
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }
}
