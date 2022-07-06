<?php

namespace Tests\Unit\src\Application\Relationship\Modules\Company\Domain;

use Core\Application\Relationship\Modules\Company\Domain\CompanyEntity as Entity;
use Core\Application\Relationship\Modules\Company\Events\AddValueEvent;
use Core\Application\Relationship\Modules\Company\Events\RemoveValueEvent;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CompanyEntityTest extends TestCase
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

    public function testAddValue()
    {
        $objRelationship = Entity::create('teste');
        $objRelationship->addValue(100, $idPayment = Uuid::uuid4());
        $this->assertEquals(100, $objRelationship->value);
        $this->assertCount(1, $objRelationship->events);
        $this->assertInstanceOf(AddValueEvent::class, $objRelationship->events[0]);
        $this->assertEquals('company.value.add.' . $objRelationship->id(), $objRelationship->events[0]->name());
        $this->assertEquals([
            'id' => $objRelationship->id(),
            'value' => $objRelationship->value,
            'payment' => (string) $idPayment,
        ], $objRelationship->events[0]->payload());
    }

    public function testRemoveValue()
    {
        $objRelationship = Entity::create('teste');
        $objRelationship->removeValue(100, $idPayment = Uuid::uuid4());
        $this->assertEquals(-100, $objRelationship->value);
        $this->assertCount(1, $objRelationship->events);
        $this->assertInstanceOf(RemoveValueEvent::class, $objRelationship->events[0]);
        $this->assertEquals('company.value.remove.' . $objRelationship->id(), $objRelationship->events[0]->name());
        $this->assertEquals([
            'id' => $objRelationship->id(),
            'value' => 100,
            'payment' => (string) $idPayment,
        ], $objRelationship->events[0]->payload());
    }
}
