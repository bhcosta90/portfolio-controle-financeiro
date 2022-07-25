<?php

namespace Tests\Unit\src\Application\Transaction\Domain;

use Core\Application\Transaction\Domain\TransactionEntity;
use Tests\UnitCase as TestCase;

class TransactionEntityTest extends TestCase
{
    public function testCreate()
    {
        $objEntity = TransactionEntity::create(
            $tenant = $this->id(),
            $this->id(),
            'test',
            $tenant,
            $this->id(),
            '1',
            'test',
            null,
            null,
            null,
            50,
            1,
            date('Y-m-d'),
            1,
            $id = $this->id()
        );
        $this->assertNotEmpty($objEntity->id());
        $this->assertNotEmpty($objEntity->createdAt());

        $this->assertCount(1, $objEntity->events);
        $this->assertEquals([
            'id' => $id,
            'tenant' => $tenant,
        ], $objEntity->events[0]->payload());
    }
}
