<?php

namespace Database\Seeders;

use App\Models\Cost;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        $this->call(UserBrunoCostaSeeder::class);

        dump(\App\Models\User::factory(10)->create()->toArray());
    }


}
