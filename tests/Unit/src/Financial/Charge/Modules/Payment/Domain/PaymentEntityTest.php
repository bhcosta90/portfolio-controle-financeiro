<?php

namespace Tests\Unit\src\Financial\Charge\Modules\Payment\Domain;

use Core\Financial\Charge\Modules\Payment\Domain\PaymentEntity as Entity;
use Core\Financial\Charge\Modules\Payment\Events\{PaymentPayEvent, PaymentCancelEvent};
use Core\Financial\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Shared\ValueObjects\UuidObject;
use PHPUnit\Framework\TestCase;

class PaymentEntityTest extends TestCase
{
    public function testCreate()
    {
        $obj = $this->getEntity();
        $this->assertNotEmpty($obj->id());
        $this->assertNotEmpty($obj->createdAt());
    }

    public function testUpdate()
    {
        $company = CompanyEntity::create('bruno costa 1234', null, null);
        $obj = $this->getEntity();
        $companyOld = $obj->company;
        $obj->update(50, $company, date('Y-m-d'), null);
        $this->assertEquals('bruno costa 1234', $obj->company->name->value);
        $this->assertEquals(50, $obj->value);
        $this->assertNotEquals($companyOld->id(), $obj->company->id());
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
        $this->assertInstanceOf(PaymentPayEvent::class, $obj->events[0]);
        $this->assertEquals('charge.payment.pay.' . $obj->id(), $obj->events[0]->name());
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
        $this->assertInstanceOf(PaymentCancelEvent::class, $obj->events[0]);
        $this->assertEquals('charge.payment.cancel.' . $obj->id(), $obj->events[0]->name());
        $this->assertEquals([
            'id' => $obj->id(),
            'value' => 25,
        ], $obj->events[0]->payload());
    }

    private function getEntity(
        $group = null,
        $value = 0.01,
        $company = null,
        int $type = 1,
        string $date = null,
        $createdAt = null,
        float $pay = 0,
        int $status = 1,
    ) {
        if (empty($company)) {
            $company = CompanyEntity::create('bruno costa', null, null);
        }

        return Entity::create(
            group: $group ? new UuidObject($group) : UuidObject::random(),
            value: $value,
            company: $company,
            type: $type,
            date: $date ?: date('Y-m-d'),
            createdAt: $createdAt,
            recurrence: null,
            pay: $pay,
            status: $status,
        );
    }
}
