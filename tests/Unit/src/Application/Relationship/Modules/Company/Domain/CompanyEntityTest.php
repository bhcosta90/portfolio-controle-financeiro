<?php

namespace Tests\Unit\src\Application\Relationship\Modules\Company\Domain;

use Core\Application\Relationship\Modules\Company\Domain\CompanyEntity as Entity;
use Tests\UnitCase;

class CompanyEntityTest extends UnitCase
{
    public function testCreate()
    {
        $objEntity = Entity::create($this->id(), "test", 50);
        $this->assertNotEmpty($objEntity->id());
        $this->assertNotEmpty($objEntity->createdAt());
        
        $this->assertNotEmpty($objEntity->account);
        $this->assertNotEmpty($objEntity->account->entity);
        $this->assertEquals(50, $objEntity->value);
        $this->assertEquals(50, $objEntity->account->value);
        $this->assertEquals($objEntity->id(), $objEntity->account->entity->id);
        $this->assertEquals(Entity::class, $objEntity->account->entity->type);

    }

    public function testEdit()
    {
        $objEntity = Entity::create($this->id(), "test", 50, $id = $this->id(), $id, "2022-01-01 00:00:00");
        $objEntity->update("update");
        $this->assertEquals("update", $objEntity->name->value);
        $this->assertEquals($id, $objEntity->id());
        $this->assertEquals("2022-01-01 00:00:00", $objEntity->createdAt());
        $this->assertEquals(50, $objEntity->value);
        $this->assertEquals(50, $objEntity->account->value);
        $this->assertEquals($id, $objEntity->account->entity->id);
        $this->assertEquals(Entity::class, $objEntity->account->entity->type);
    }

    public function testAddValue()
    {
        $objEntity = Entity::create($this->id(), "test", 50);
        $objEntity->addValue(150, $this->id());
        $this->assertEquals(200, $objEntity->value);
        $this->assertEquals(200, $objEntity->account->value);
    }

    public function testSubValue()
    {
        $objEntity = Entity::create($this->id(), "test", 50);
        $objEntity->removeValue(150, $this->id());
        $this->assertEquals(-100, $objEntity->value);
        $this->assertEquals(-100, $objEntity->account->value);
    }
}
