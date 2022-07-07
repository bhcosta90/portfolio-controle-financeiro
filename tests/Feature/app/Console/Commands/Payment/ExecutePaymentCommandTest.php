<?php

namespace Tests\Feature\app\Console\Commands\Payment;

use App\Models\Payment;
use App\Models\Relationship;
use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use DateTime;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ExecutePaymentCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function testExecuteCommand()
    {
        $objRelationship = Relationship::factory()->create(['entity' => CustomerEntity::class, 'value' => 0]);

        $basePayment = [
            'relationship_id' => $objRelationship->id,
            'relationship_type' => $objRelationship->entity,
            'value' => 50,
            'type' => 2,
        ];

        $data = Payment::factory(3)->create([
                'date' => (new DateTime())->modify('+10 minute')->format('Y-m-d H:i:s'),
            ] + $basePayment);

        $dataPayment = Payment::factory(3)->create([
                'date' => (new DateTime())->modify('-2 minute')->format('Y-m-d H:i:s'),
            ] + $basePayment);

        $this->artisan('payment:execute');

        $this->assertDatabaseHas('payments', [
            'id' => $data[0]->id,
            'status' => 1,
            'value' => 50,
        ]);

        $this->assertDatabaseHas('payments', [
            'id' => $dataPayment[0]->id,
            'status' => 3,
            'value' => 50,
        ]);

        $this->assertDatabaseHas('relationships', [
            'id' => $objRelationship->id,
            'value' => 150,
        ]);
    }
}
