<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $relationship = $this->command->ask('Please enter id customer or id company!!');
        $objRelationship = DB::table('relationships')->select('id', 'name', 'tenant_id', 'entity')->find($relationship);
        $limit = $this->command->ask('Please enter the limit!!');
        \App\Models\Payment::factory($limit)->create([
            'tenant_id' => $objRelationship->tenant_id,
            'relationship_id' => $objRelationship->id,
            'relationship_name' => $objRelationship->name,
            'relationship_type' => $objRelationship->entity
        ]);
    }
}
