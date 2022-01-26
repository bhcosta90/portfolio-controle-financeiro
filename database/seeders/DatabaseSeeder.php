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
            'user_id' => $user->id,
            'name' => "NU PAGAMENTOS S.A.",
            'value' => 0,
            'bank_code' => '0260',
            'bank_agency' => '0001',
            'bank_account' => '9954491',
            'bank_digit' => '3',
            'type' => \App\Models\Account::TYPE_PAYMENT,
        ]);
    }
}
