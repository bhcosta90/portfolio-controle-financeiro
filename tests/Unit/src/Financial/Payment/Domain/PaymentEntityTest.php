<?php

namespace Tests\Unit\src\Financial\Payment\Domain;

use Core\Financial\Account\Domain\AccountEntity;
use Core\Financial\Payment\Domain\PaymentEntity;
use Core\Financial\Payment\Events\PayEvent;
use Core\Shared\ValueObjects\EntityObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class PaymentEntityTest extends TestCase
{
    public function testCreate()
    {
        $obj = $this->getEntity(
            accountFrom: $accountFrom = Uuid::uuid4(),
            accountTo: $accountTo = Uuid::uuid4()
        );
        $this->assertNotEmpty($obj->id());
        $this->assertNotEmpty($obj->createdAt());
        $this->assertTrue($obj->completed);
        $this->assertEquals(1, $obj->status->value);
        $this->assertCount(1, $obj->events);
        $this->assertInstanceOf(PayEvent::class, $obj->events[0]);
        $this->assertEquals([
            'id' => $obj->id(),
            'value' => 50,
            'account_from' => $accountFrom,
            'account_to' => $accountTo,
        ], $obj->events[0]->publish());
        $this->assertEquals('payment.execute.' . $obj->id(), $obj->events[0]->name());
    }

    private function getEntity(
        $value = 50,
        $date = null,
        $id = null,
        $accountFrom = null,
        $accountTo = null,
    ) {
        return PaymentEntity::create(
            $value,
            $date ?: date('Y-m-d'),
            $n = new EntityObject($id ?: Uuid::uuid4(), 'teste'),
            $accountFrom ? AccountEntity::create($n, 0, $accountFrom) : null,
            $accountTo ? AccountEntity::create($n, 0, $accountTo) : null,
        );
    }
}
