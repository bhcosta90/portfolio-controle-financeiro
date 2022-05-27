<?php

namespace Tests\Unit\Costa\Modules\Relationship\Entities;

use Costa\Modules\Relationship\Entities\CustomerEntity as Entity;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;
use Mockery;
use PHPUnit\Framework\TestCase;

class CustomerEntityTest extends TestCase
{
    public function testBasicEntity()
    {
        $obj = new Entity(
            name: $this->mockInputNameObject('bruno costa'),
        );

        $this->assertEquals('bruno costa', $obj->name->value);
        $this->assertNotEmpty($obj->id());
        $this->assertNotEmpty($obj->createdAt());
    }

    public function testEntity()
    {
        $obj = new Entity(
            name: $this->mockInputNameObject('bruno costa'),
            id: $id = UuidObject::random(),
            createdAt: $date = new DateTime()
        );

        $this->assertEquals('bruno costa', $obj->name->value);
        $this->assertEquals($id, $obj->id());
        $this->assertEquals($date->format('Y-m-d H:i:s'), $obj->createdAt());
    }

    public function testUpdateEntity()
    {
        $obj = new Entity(
            name: $this->mockInputNameObject('bruno costa'),
        );

        $obj->update(name: $this->mockInputNameObject('bruno costa 123'));
        
        $this->assertEquals('bruno costa 123', $obj->name->value);
        $this->assertNotEmpty($obj->id());
        $this->assertNotEmpty($obj->createdAt());
    }

    /** @return InputNameObject|Mockery\MockInterface */
    protected function mockInputNameObject(string $value = 'teste')
    {
        return Mockery::mock(stdClass::class, InputNameObject::class, [$value]);
    }
}
