<?php

namespace Tests\Unit\src\Financial\Payment\Domain;

use Core\Financial\Payment\Domain\PaymentEntity;
use Core\Shared\ValueObjects\EntityObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class PaymentEntityTest extends TestCase
{
    public function testCreate()
    {
        $obj = $this->getEntity();
        $this->assertNotEmpty($obj->id());
        $this->assertNotEmpty($obj->createdAt());
        $this->assertTrue($obj->completed);
        $this->assertEquals(1, $obj->status->value);
    }

    private function getEntity(
        $value = 50,
        $date = null,
        $id = null,
    ) {
        return PaymentEntity::create(
            $value,
            $date ?: date('Y-m-d'),
            new EntityObject($id ?: Uuid::uuid4(), 'teste'),
            null,
        );
    }
}
