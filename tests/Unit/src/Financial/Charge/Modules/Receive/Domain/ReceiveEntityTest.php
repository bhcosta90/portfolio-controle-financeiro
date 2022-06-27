<?php

namespace Tests\Unit\src\Financial\Charge\Modules\Receive\Domain;

use Core\Financial\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Financial\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;
use Core\Financial\Charge\Modules\Receive\Events\{ReceivePayEvent, ReceiveCancelEvent};

use Core\Shared\ValueObjects\UuidObject;
use PHPUnit\Framework\TestCase;

class ReceiveEntityTest extends TestCase
{
    public function testCreate()
    {
        $obj = $this->getEntity();
        $this->assertNotEmpty($obj->id());
        $this->assertNotEmpty($obj->createdAt());
    }

    public function testUpdate()
    {
        $customer = CustomerEntity::create('bruno costa 1234', null, null);
        $obj = $this->getEntity();
        $customerOld = $obj->customer;
        $obj->update(50, $customer, '2022-01-01', null);
        $this->assertEquals('bruno costa 1234', $obj->customer->name->value);
        $this->assertEquals(50, $obj->value);
        $this->assertNotEquals($customerOld->id(), $obj->customer->id());
    }

    public function testPaymentChargeError()
    {
        $this->expectExceptionMessage('This payment is greater than the amount charged');
        $obj = $this->getEntity(value: 50);
        $obj->pay(100);
    }

    public function testPaymentChargePartial()
    {
        $obj = $this->getEntity(value: 50);
        $obj->pay(25);
        $this->assertEquals(2, $obj->status->value);
    }

    public function testPaymentChargeComplete()
    {
        $obj = $this->getEntity(value: 50);
        $obj->pay(50);
        $this->assertEquals(3, $obj->status->value);
    }

    public function testPaymentChargeWithValuePayError(){
        $this->expectExceptionMessage('This payment is greater than the amount charged');
        $obj = $this->getEntity(value: 50, pay: 10);
        $obj->pay(50);
    }

    public function testPaymentChargeWithValuePay(){
        $obj = $this->getEntity(value: 50, pay: 10);
        $obj->pay(40);
        $this->assertEquals(3, $obj->status->value);
    }

    public function testCancelError(){
        $this->expectExceptionMessage('This charge has not been paid');
        $obj = $this->getEntity(value: 50);
        $obj->cancel(10);
    }

    public function testCancel(){
        $obj = $this->getEntity(value: 50, pay: 50, status: 3);
        $obj->cancel(50);
        $this->assertEquals(1, $obj->status->value);
    }

    public function testCancelPartial(){
        $obj = $this->getEntity(value: 50, pay: 50, status: 3);
        $obj->cancel(10);
        $this->assertEquals(2, $obj->status->value);
    }

    public function testChargePayEvent(){
        $obj = $this->getEntity(value: 50);
        $obj->pay(25);
        $this->assertCount(1, $obj->events);
        $this->assertInstanceOf(ReceivePayEvent::class, $obj->events[0]);
        $this->assertEquals('charge.receive.pay.' . $obj->id(), $obj->events[0]->name());
        $this->assertEquals([
            'id' => $obj->id(),
            'value' => 50,
            'pay' => 25,
        ], $obj->events[0]->payload());
    }

    public function testChargeCancelEvent(){
        $obj = $this->getEntity(value: 50, pay: 50);
        $obj->cancel(25);
        $this->assertCount(1, $obj->events);
        $this->assertInstanceOf(ReceiveCancelEvent::class, $obj->events[0]);
        $this->assertEquals('charge.receive.cancel.' . $obj->id(), $obj->events[0]->name());
        $this->assertEquals([
            'id' => $obj->id(),
            'value' => 25,
        ], $obj->events[0]->payload());
    }

    private function getEntity(
        $group = null,
        $value = 0.01,
        $customer = null,
        int $type = 1,
        string $date = null,
        $createdAt = null,
        $status = 1,
        $pay = 0,
    ) {
        if (empty($customer)) {
            $customer = CustomerEntity::create('bruno costa', null, null);
        }

        return Entity::create(
            group: $group ? new UuidObject($group) : UuidObject::random(),
            value: $value,
            customer: $customer,
            type: $type,
            date: $date ?: date('Y-m-d'),
            createdAt: $createdAt,
            recurrence: null,
            status: $status,
            pay: $pay,
        );
    }
}
