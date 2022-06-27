<?php

namespace Tests\Unit\src\Financial\Charge\Modules\Receive\Domain;

use Core\Financial\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Financial\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;
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
        $obj->update(50, $customer);
        $this->assertEquals('bruno costa 1234', $obj->customer->name->value);
        $this->assertEquals(50, $obj->value);
        $this->assertNotEquals($customerOld->id(), $obj->customer->id());
    }

    private function getEntity(
        $group = null,
        $value = 0.01,
        $customer = null,
        int $type = 1,
        string $date = null,
        $createdAt = null,
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
        );
    }
}
