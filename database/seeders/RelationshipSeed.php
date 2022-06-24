<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Relationship;
use Illuminate\Database\Seeder;

class RelationshipSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $limit = $this->command->ask('Please enter the limit for creating relationship!!');

        if ($limit > 0) {
            Relationship::factory($limit)->create(['tenant_id' => 'c606b480-a559-48f8-9737-0b896442ab25'])
                ->each(function ($obj) {
                    Account::create([
                        'id' => str()->uuid(),
                        'value' => 0,
                        'entity_type' => $obj->entity,
                        'entity_id' => $obj->id,
                    ]);
                });
        }
    }
}
