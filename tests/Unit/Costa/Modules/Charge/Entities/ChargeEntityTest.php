<?php

namespace Tests\Unit\Costa\Modules\Charge\Entities;

use Costa\Modules\Charge\Entities\ChargeEntity as EntitiesChargeEntity;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\Input\InputValueObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Shareds\ValueObjects\MockModelObjectTrait;

class ChargeEntityTest extends TestCase
{
    use MockModelObjectTrait;

    public function testBasic()
    {
        $obj = new ChargeEntity(
            title: $this->mockInputNameObject('teste'),
            description: $this->mockInputNameObject('description'),
            relationship: $this->mockModelObject(),
            value: $this->mockInputValueObject(50),
            date: new DateTime(),
            base: UuidObject::random(),
        );
        $this->assertNotEmpty($obj->id());
        $this->assertNotEmpty($obj->createdAt());
    }

    public function testEntity()
    {
        $obj = new ChargeEntity(
            title: $this->mockInputNameObject('teste'),
            description: $this->mockInputNameObject('description'),
            relationship: $this->mockModelObject(),
            value: $this->mockInputValueObject(50),
            date: new DateTime(),
            id: $id = UuidObject::random(),
            createdAt: $date = new DateTime('2020-01-01 00:00:00'),
            base: UuidObject::random(),
        );

        $this->assertEquals($id, $obj->id);
        $this->assertEquals($date, $obj->createdAt);
    }

    public function testUpdate()
    {
        $obj = new ChargeEntity(
            title: $this->mockInputNameObject('teste'),
            description: $this->mockInputNameObject('description'),
            relationship: $this->mockModelObject(),
            value: $this->mockInputValueObject(),
            date: new DateTime(),
            base: UuidObject::random(),
        );

        $this->assertEquals(1, $obj->relationship->id);

        $obj->update(
            title: $this->mockInputNameObject('teste'),
            description: $this->mockInputNameObject('teste2'),
            relationship: $this->mockModelObject(2),
            value: $this->mockInputValueObject(100),
            date: new DateTime('2020-01-01 00:00:00'),
        );

        $this->assertEquals('teste', $obj->title->value);
        $this->assertEquals('teste2', $obj->description->value);
        $this->assertEquals(2, $obj->relationship->id);
        $this->assertEquals(100, $obj->value->value);
        $this->assertEquals('2020-01-01', $obj->date->format('Y-m-d'));
    }

    /** @return InputNameObject|Mockery\MockInterface */
    protected function mockInputNameObject(string $value = 'teste')
    {
        return Mockery::mock(stdClass::class, InputNameObject::class, [$value]);
    }

    /** @return InputValueObject|Mockery\MockInterface */
    protected function mockInputValueObject(float $value = 50)
    {
        return Mockery::mock(stdClass::class, InputValueObject::class, [$value]);
    }
}

class ChargeEntity extends EntitiesChargeEntity
{
    //
}
