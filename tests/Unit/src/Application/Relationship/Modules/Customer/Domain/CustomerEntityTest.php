<?php

namespace Tests\Unit\src\Application\Relationship\Modules\Customer\Domain;

use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity as Entity;
use Core\Application\Relationship\Modules\Customer\Events\{AddValueEvent, RemoveValueEvent};
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CustomerEntityTest extends TestCase
{
    public function testCreate()
    {
        $objRelationship = Entity::create('teste');

        $this->assertNotEmpty($objRelationship->id());
        $this->assertNotEmpty($objRelationship->createdAt());
    }

    public function testUpdate()
    {
        $objRelationship = Entity::create('teste', 0, $id = Uuid::uuid4());

        $objRelationship->update(
            name: 'teste 2',
        );

        $this->assertEquals($id, $objRelationship->id());
        $this->assertEquals('teste 2', $objRelationship->name->value);
    }

    public function testAddCredit()
    {
        $objRelationship = Entity::create('teste');
        $objRelationship->addValue(100, Uuid::uuid4());
        $this->assertEquals(100, $objRelationship->value);
        $this->assertCount(1, $objRelationship->events);
        $this->assertInstanceOf(AddValueEvent::class, $objRelationship->events[0]);
        $this->assertEquals('customer.value.add.' . $objRelationship->id(), $objRelationship->events[0]->name());
        $this->assertEquals([
            'id' => $objRelationship->id(),
            'value' => $objRelationship->value,
        ], $objRelationship->events[0]->payload());
    }

    public function testRemoveCredit()
    {
        $objRelationship = Entity::create('teste');
        $objRelationship->removeValue(100, Uuid::uuid4());
        $this->assertEquals(-100, $objRelationship->value);
        $this->assertCount(1, $objRelationship->events);
        $this->assertInstanceOf(RemoveValueEvent::class, $objRelationship->events[0]);
        $this->assertEquals('customer.value.remove.' . $objRelationship->id(), $objRelationship->events[0]->name());
        $this->assertEquals([
            'id' => $objRelationship->id(),
            'value' => 100,
        ], $objRelationship->events[0]->payload());
    }
}
