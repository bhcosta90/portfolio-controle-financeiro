<?php

namespace Tests\Unit\src\Financial\BankAccount\Domain;

use PHPUnit\Framework\TestCase;
use Core\Financial\BankAccount\Domain\BankAccountEntity as Entity;
use DateTime;

class BankAccountEntityTest extends TestCase
{
    public function testCreate()
    {
        $obj = $this->getEntity();
        $this->assertNotEmpty($obj->id());
        $this->assertNotEmpty($obj->createdAt());
    }

    public function testEntity()
    {
        $obj = $this->getEntity(
            id: $id = str()->uuid(),
            createdAt: $date = (new DateTime())->modify('-1 day')->format('Y-m-d H:i:s')
        );

        $this->assertEquals($id, $obj->id());
        $this->assertEquals($date, $obj->createdAt());
    }

    public function testUpdate()
    {
        $obj = $this->getEntity();
        $obj->update(name: 'bruno costa 1234');
        $this->assertEquals('bruno costa 1234', $obj->name->value);
    }

    private function getEntity(
        $name = 'bruno costa',
        $value = 0,
        $id = null,
        $createdAt = null,
    ) {
        return Entity::create(
            name: $name,
            value: $value,
            id: $id,
            createdAt: $createdAt,
        );
    }
}
