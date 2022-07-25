<?php

namespace Tests\Unit\src\Application\Charge\Modules\Receive\Domain;

use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;
use Core\Application\Charge\Modules\Receive\Events\{AddPayEvent, RemovePayEvent};
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ReceiveEntityTest extends TestCase
{
    public function testCreate()
    {
        $objCharge = Entity::create(
            tenant: Uuid::uuid4(),
            title: 'teste',
            resume: null,
            customer: Uuid::uuid4(),
            recurrence: Uuid::uuid4(),
            value: 50,
            pay: null,
            group: Uuid::uuid4(),
            date: '2022-01-01',
            status: 1
        );

        $this->assertNotEmpty($objCharge->id());
        $this->assertNotEmpty($objCharge->createdAt());
    }

    public function testUpdate()
    {
        $objCharge = Entity::create(
            tenant: Uuid::uuid4(),
            title: 'teste',
            resume: null,
            customer: $customer = Uuid::uuid4(),
            recurrence: $recurrence = Uuid::uuid4(),
            value: 50,
            pay: null,
            group: Uuid::uuid4(),
            date: '2022-01-01',
            status: 1,
            id: $id = Uuid::uuid4()
        );

        $this->assertEquals($id, $objCharge->id());
        $this->assertEquals($customer, (string)$objCharge->customer->id);
        $this->assertEquals($recurrence, (string)$objCharge->recurrence);

        $objCharge->update(
            title: 'teste2',
            resume: 'resume2',
            customer: $customer = Uuid::uuid4(),
            recurrence: $recurrence = Uuid::uuid4(),
            value: 50,
            date: '2022-01-02',
        );

        $this->assertEquals('teste2', $objCharge->title->value);
        $this->assertEquals('resume2', $objCharge->resume->value);
        $this->assertEquals($customer, (string)$objCharge->customer->id);
        $this->assertEquals($recurrence, (string)$objCharge->recurrence);
        $this->assertEquals('2022-01-02', $objCharge->date->format('Y-m-d'));
        $this->assertEquals(50, $objCharge->value->value);
    }

    public function testPayExceptionV01()
    {
        $this->expectExceptionMessage('The receive is greater than the amount of the account payable');

        $objCharge = Entity::create(
            tenant: Uuid::uuid4(),
            title: 'teste',
            resume: null,
            customer: Uuid::uuid4(),
            recurrence: Uuid::uuid4(),
            value: 50,
            pay: null,
            group: Uuid::uuid4(),
            date: '2022-01-01',
            status: 1,
            id: Uuid::uuid4()
        );

        $objCharge->pay(100);
    }

    public function testPayExceptionV02()
    {
        $this->expectExceptionMessage('The receive is greater than the amount of the account payable');

        $objCharge = Entity::create(
            tenant: Uuid::uuid4(),
            title: 'teste',
            resume: null,
            customer: Uuid::uuid4(),
            recurrence: Uuid::uuid4(),
            value: 50,
            pay: 1,
            group: Uuid::uuid4(),
            date: '2022-01-01',
            status: 1,
            id: Uuid::uuid4()
        );

        $objCharge->pay(50);
    }

    public function testPayPartial()
    {
        $objCharge = Entity::create(
            tenant: Uuid::uuid4(),
            title: 'teste',
            resume: null,
            customer: Uuid::uuid4(),
            recurrence: Uuid::uuid4(),
            value: 50,
            pay: null,
            group: Uuid::uuid4(),
            date: '2022-01-01',
            status: 1,
            id: Uuid::uuid4()
        );

        $ret = $objCharge->pay(30);
        $this->assertEquals(2, $objCharge->status->value);
        $this->assertEquals(20, $ret);
    }

    public function testPayComplete()
    {
        $objCharge = Entity::create(
            tenant: Uuid::uuid4(),
            title: 'teste',
            resume: null,
            customer: Uuid::uuid4(),
            recurrence: Uuid::uuid4(),
            value: 50,
            pay: null,
            group: Uuid::uuid4(),
            date: '2022-01-01',
            status: 1,
            id: Uuid::uuid4()
        );

        $ret = $objCharge->pay(50);
        $this->assertEquals(2, $objCharge->status->value);
        $this->assertEquals(0, $ret);
    }

    public function testCancelException()
    {
        $this->expectExceptionMessage('This receive cannot be canceled as it leaves the charge amount less than 0');

        $objCharge = Entity::create(
            tenant: Uuid::uuid4(),
            title: 'teste',
            resume: null,
            customer: Uuid::uuid4(),
            recurrence: Uuid::uuid4(),
            value: 50,
            pay: 40,
            group: Uuid::uuid4(),
            date: '2022-01-01',
            status: 1,
            id: Uuid::uuid4()
        );

        $objCharge->cancel(50);
    }

    public function testCancelPartial()
    {
        $objCharge = Entity::create(
            tenant: Uuid::uuid4(),
            title: 'teste',
            resume: null,
            customer: Uuid::uuid4(),
            recurrence: Uuid::uuid4(),
            value: 50,
            pay: 40,
            group: Uuid::uuid4(),
            date: '2022-01-01',
            status: 1,
            id: Uuid::uuid4()
        );

        $objCharge->cancel(10);
        $this->assertEquals(30, $objCharge->pay->value);
        $this->assertEquals(1, $objCharge->status->value);
    }

    public function testCancelComplete()
    {
        $objCharge = Entity::create(
            tenant: Uuid::uuid4(),
            title: 'teste',
            resume: null,
            customer: Uuid::uuid4(),
            recurrence: Uuid::uuid4(),
            value: 50,
            pay: 40,
            group: Uuid::uuid4(),
            date: '2022-01-01',
            status: 1,
            id: Uuid::uuid4()
        );

        $objCharge->cancel(40);
        $this->assertEquals(0, $objCharge->pay->value);
        $this->assertEquals(1, $objCharge->status->value);
    }
}
