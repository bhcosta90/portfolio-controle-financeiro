<?php

namespace Tests\Unit\src\Financial\Relationship\Modules\Customer\Domain;

use Core\Financial\Relationship\Modules\Customer\Domain\CustomerEntity as Entity;
use DateTime;
use PHPUnit\Framework\TestCase;

class CustomerEntityTest extends TestCase
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
        $obj->update(name: 'bruno costa 1234', document_type: 2, document_value: '79057580000103');
        $this->assertEquals('bruno costa 1234', $obj->name->value);
        $this->assertEquals(2, $obj->document->type->value);
        $this->assertEquals('79057580000103', $obj->document->document);
    }

    private function getEntity(
        $name = 'bruno costa',
        $document = '17964626000110',
        $id = null,
        $createdAt = null,
    ) {
        return Entity::create(
            name: $name,
            document_value: $document,
            document_type: 2,
            id: $id,
            createdAt: $createdAt,
        );
    }
}
