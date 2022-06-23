<?php

namespace Tests\Unit\Financial\Relationship\Domains;

use Core\Financial\Relationship\Domain\RelationshipEntityAbstract;
use PHPUnit\Framework\TestCase;

class RelationshipEntityAbstractTest extends TestCase
{
    public function testCreate()
    {
        $obj = $this->getEntity();
        $this->assertNotEmpty($obj->id());
        $this->assertNotEmpty($obj->createdAt());
    }

    public function testUpdate()
    {
        $obj = $this->getEntity();
        $obj->update(name: 'bruno costa 1234', document: '79057580000103');
        $this->assertEquals('bruno costa 1234', $obj->name->value);
        $this->assertEquals('79057580000103', $obj->document);
    }

    private function getEntity(
        $name = 'bruno costa',
        $document = '17964626000110'
    ) {
        return StubRelationshipEntity::create(name: $name, document: $document);
    }
}

class StubRelationshipEntity extends RelationshipEntityAbstract
{
    //
}
