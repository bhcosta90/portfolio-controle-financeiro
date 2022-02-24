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

        dump(\App\Models\User::orderBy('id')->pluck('tenant_id', 'email')->toArray());
    }
}
