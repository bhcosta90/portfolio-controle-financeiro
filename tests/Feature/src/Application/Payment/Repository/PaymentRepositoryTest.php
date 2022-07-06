<?php

namespace Tests\Feature\src\Application\Payment\Repository;

use App\Models\AccountBank;
use App\Models\Charge;
use App\Models\Payment as Model;
use App\Models\Payment;
use App\Models\Relationship;
use App\Repository\Eloquent\PaymentEloquent as Eloquent;
use Core\Application\Payment\Domain\PaymentEntity as Entity;
use Core\Application\Payment\Repository\PaymentRepository as Repository;
use Core\Shared\ValueObjects\EntityObject;
use DateTime;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PaymentRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testInsert()
    {
        $objRelationship = Relationship::factory()->create();
        $objCharge = Charge::factory()->create();

        $obj = Entity::create(
            relationship: new EntityObject($objRelationship->id, $objRelationship->entity),
            charge: new EntityObject($objCharge->id, $objCharge->entity),
            bank: null,
            value: 50,
            status: null,
            type: 1,
            date: $date = date('Y-m-d', strtotime('+1 day')),
            title: 'teste',
            resume: 'teste',
            name: 'teste',
        );
        $this->assertTrue($this->getPaymentRepository()->insert($obj));

        $this->assertDatabaseHas('payments', [
            'id' => $obj->id(),
            'date' => $date . ' 10:00:00',
            'status' => 1,
            'account_bank_id' => null,
        ]);
    }

    public function testInsertWithBank()
    {
        $objRelationship = Relationship::factory()->create();
        $objCharge = Charge::factory()->create();
        $objAccountBank = AccountBank::factory()->create();

        $obj = Entity::create(
            relationship: new EntityObject($objRelationship->id, $objRelationship->entity),
            charge: new EntityObject($objCharge->id, $objCharge->entity),
            bank: $objAccountBank->id,
            value: 50,
            status: null,
            type: 1,
            date: date('Y-m-d', strtotime('+1 day')),
            title: 'teste',
            resume: 'teste',
            name: 'teste',
        );
        $this->assertTrue($this->getPaymentRepository()->insert($obj));

        $this->assertDatabaseHas('payments', [
            'id' => $obj->id(),
            'status' => 1,
            'account_bank_id' => $objAccountBank->id,
        ]);
    }

    public function testWithoutDate()
    {
        $objRelationship = Relationship::factory()->create();
        $objCharge = Charge::factory()->create();

        $obj = Entity::create(
            relationship: new EntityObject($objRelationship->id, $objRelationship->entity),
            charge: new EntityObject($objCharge->id, $objCharge->entity),
            bank: null,
            value: 50,
            status: null,
            type: 1,
            date: null,
            title: 'teste',
            resume: 'teste',
            name: 'teste',
        );

        $this->getPaymentRepository()->insert($obj);

        $objPayment = Model::find($obj->id());
        $datePayment = new DateTime($objPayment->date);

        $dateActual = (new DateTime())->getTimestamp();
        $this->assertTrue(($dateActual + 62) > $datePayment->getTimestamp());
    }

    public function testUpdateStatus()
    {
        $data = Payment::factory(5)->create(['date' => date('Y-m-d H:i:s', strtotime('+15 minute'))]);
        $dataUpdate = Payment::factory(5)->create(['date' => date('Y-m-d H:i:s', strtotime('+15 seconds'))]);
        $this->getPaymentRepository()->updateStatus(date('Y-m-d H:i:s', strtotime('+1 minute')), 1, 2);

        $this->assertDatabaseHas('payments', [
            'id' => $data[0]->id,
            'status' => 1,
        ]);

        $this->assertDatabaseHas('payments', [
            'id' => $dataUpdate[0]->id,
            'status' => 2,
        ]);
    }

    public function testGetListStatusWith_3Items()
    {
        Payment::factory(5)->create(['date' => date('Y-m-d H:i:s', strtotime('+15 minute'))]);
        Payment::factory(5)->create(['date' => date('Y-m-d H:i:s', strtotime('+15 seconds'))]);
        $this->getPaymentRepository()->updateStatus(date('Y-m-d H:i:s', strtotime('+1 minute')), 1, 2);

        $list = $this->getPaymentRepository()->getListStatus(2, 3);
        $this->assertCount(3, $list->items());
    }

    public function testGetListStatus()
    {
        Payment::factory(5)->create(['date' => date('Y-m-d H:i:s', strtotime('+15 minute'))]);
        Payment::factory(5)->create(['date' => date('Y-m-d H:i:s', strtotime('+15 seconds'))]);
        $this->getPaymentRepository()->updateStatus(date('Y-m-d H:i:s', strtotime('+1 minute')), 1, 2);

        $list = $this->getPaymentRepository()->getListStatus(2);
        $this->assertCount(5, $list->items());
    }

    private function getPaymentRepository(): Eloquent
    {
        return app(Repository::class);
    }
}
