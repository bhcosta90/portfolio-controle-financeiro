<?php

namespace Tests\Unit\src\Financial\Recurrence\Domain;

use DateTime;
use PHPUnit\Framework\TestCase;
use Core\Financial\Recurrence\Domain\RecurrenceEntity as Entity;

class RecurrenceEntityTest extends TestCase
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
        $obj->update(name: 'bruno costa 1234', days: 2);
        $this->assertEquals('bruno costa 1234', $obj->name->value);
        $this->assertEquals(2, $obj->days);
    }

    public function testCalculateDays()
    {

        $objEntity = $this->getEntity(days: 30);
        $date = $objEntity->calculate("2022-05-31");
        $this->assertEquals('2022-06-30', $date->format('Y-m-d'));

        $objEntity = $this->getEntity();
        $date = $objEntity->calculate("2022-06-24");
        $this->assertEquals('2022-08-13', $date->format('Y-m-d'));

        $objEntity = $this->getEntity(days: 30);
        $date = $objEntity->calculate("2022-06-24");
        $this->assertEquals('2022-07-24', $date->format('Y-m-d'));


        $objEntity = $this->getEntity(days: 90);
        $date = $objEntity->calculate("2022-05-31");
        $this->assertEquals('2022-08-31', $date->format('Y-m-d'));
    }

    private function getEntity(
        $name = 'bruno costa',
        $days = 50,
        $id = null,
        $createdAt = null,
    ) {
        return Entity::create(
            name: $name,
            days: $days,
            id: $id,
            createdAt: $createdAt,
        );
    }
}
