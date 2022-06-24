<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\BankAccount;
use Core\Financial\BankAccount\Domain\BankAccountEntity;
use Illuminate\Database\Seeder;

class BankAccountSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $limit = $this->command->ask('Please enter the limit for creating bank account!!');

        if ($limit > 0) {
            BankAccount::factory($limit)->create(['tenant_id' => 'c606b480-a559-48f8-9737-0b896442ab25'])
                ->each(function ($obj) {
                    Account::create([
                        'id' => str()->uuid(),
                        'value' => 0,
                        'entity_type' => BankAccountEntity::class,
                        'entity_id' => $obj->id,
                        'value' => rand(0, 10) > 7 ? (rand(-100000, 100000) / 100) : 0
                    ]);
                });
        }
    }
}
