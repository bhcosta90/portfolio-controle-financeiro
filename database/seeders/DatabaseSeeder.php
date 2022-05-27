<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $this->register('0b26c3fd-bb33-4419-867a-5aee383353f5', env('DB_DATABASE') . '_1', 'controle-financeiro');
        $this->register('62a91d51-e600-404d-b88d-a696d6e0b693', env('DB_DATABASE') . '_2', 'controle-financeiro2');

        Artisan::call('tenants:migrate-fresh');
        Artisan::call('tenants:seed', [
            '--class' => TenantSeeder::class,
        ]);
    }

    private function register($id, $database, $url)
    {
        DB::table('tenants')->insert([
            'id' => $id,
            'data' => json_encode(['tenancy_db_name' => $database]),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('domains')->insert([
            'domain' => $url . '.localhost',
            'tenant_id' => $id,
        ]);
    }
}
