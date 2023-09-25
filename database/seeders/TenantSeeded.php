<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Category;
use App\Models\Charge\Charge;
use Illuminate\Database\Seeder;

class TenantSeeded extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account = Account::create([
            'name' => 'Minha conta corrente',
            'balance' => 0,
            'overdraft' => 0,
        ]);

        Category::factory(3)->create()->each(function($category) {
            Category::factory(rand(1,5))->create([
                'category_id' => $category->id,
            ]);
        });

        Charge::factory(100)->create(['account_id' => $account->id]);
    }
}
