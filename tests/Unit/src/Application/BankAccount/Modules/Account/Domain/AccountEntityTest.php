<?php

namespace Tests\Unit\src\Application\BankAccount\Modules\Account\Domain;

use Core\Application\BankAccount\Modules\Account\Domain\AccountEntity as Entity;
use Core\Application\BankAccount\Modules\Account\Events\{AddValueEvent, RemoveValueEvent};
use Tests\UnitCase as TestCase;

class AccountEntityTest extends TestCase
{
    public function testCreate()
    {
        $objEntity = Entity::create($this->id(), "1", "test", 50);
        $this->assertNotEmpty($objEntity->id());
        $this->assertNotEmpty($objEntity->createdAt());
        $this->assertEquals(50, $objEntity->value);
    }

    public function testAddValue()
    {
        $objEntity = Entity::create($this->id(), "1", "test", 50);
        $objEntity->addValue(150, $this->id());
        $this->assertEquals(200, $objEntity->value);
        $this->assertCount(1, $objEntity->events);
        $this->assertInstanceOf(AddValueEvent::class, $objEntity->events[0]);
        $this->assertEquals([
            'id' => $objEntity->id(),
            'value' => 150,
        ], $objEntity->events[0]->payload());
    }

    public function testSubValue()
    {
        $objEntity = Entity::create($this->id(), "1", "test", 50);
        $objEntity->removeValue(150, $this->id());
        $this->assertEquals(-100, $objEntity->value);
        $this->assertCount(1, $objEntity->events);
        $this->assertInstanceOf(RemoveValueEvent::class, $objEntity->events[0]);
        $this->assertEquals([
            'id' => $objEntity->id(),
            'value' => 150,
        ], $objEntity->events[0]->payload());
    }
}
