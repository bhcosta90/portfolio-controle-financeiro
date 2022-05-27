<?php

namespace Tests\Unit\Costa\Modules\Account\Entities;

use PHPUnit\Framework\TestCase;
use Costa\Modules\Account\Entities\AccountEntity as Entity;
use Costa\Shareds\ValueObjects\ModelObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;

class AccountEntityTest extends TestCase
{
    public function testBasicEntity()
    {
        $obj = new Entity(
            model: new ModelObject(1, 'teste'),
            value: 0,
        );

        $this->assertNotEmpty($obj->createdAt());
        $this->assertNotEmpty($obj->model);
    }

    public function testEntity()
    {
        $obj = new Entity(
            model: new ModelObject(1, 'teste'),
            value: 0,
            increment: 1,
            createdAt: $date = new DateTime()
        );

        $this->assertEquals(1, $obj->increment);
        $this->assertEquals($date->format('Y-m-d H:i:s'), $obj->createdAt());
    }
}
