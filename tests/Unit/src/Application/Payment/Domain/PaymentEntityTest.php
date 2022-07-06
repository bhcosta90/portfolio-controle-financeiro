<?php

namespace Tests\Unit\src\Application\Payment\Domain;

use PHPUnit\Framework\TestCase;
use Core\Application\Payment;
use Core\Shared\ValueObjects\EntityObject;
use DateTime;

class PaymentEntityTest extends TestCase
{
    public function testCreateWithDate()
    {
        $entity = Payment\Domain\PaymentEntity::create(
            relationship: new EntityObject(1, 'teste'),
            charge: new EntityObject(1, 'teste'),
            bank: null,
            value: 50,
            status: null,
            type: 1,
            date: $date = date('Y-m-d', strtotime('+1 day')),
            title: 'teste',
            resume: null,
            name: 'teste',
        );

        $this->assertEquals($entity->date->format('Y-m-d H:i:s'), "{$date} 10:00:00");
        $this->assertEquals(1, $entity->status->value);
        $this->assertNull($entity->bank);
    }

    public function testCreateWithoutDate()
    {
        $entity = Payment\Domain\PaymentEntity::create(
            relationship: new EntityObject(1, 'teste'),
            charge: new EntityObject(1, 'teste'),
            bank: null,
            value: 50,
            status: null,
            type: 1,
            date: null,
            title: 'teste',
            resume: null,
            name: 'teste',
        );

        $dateActual = (new DateTime())->getTimestamp();
        $this->assertNotEmpty($entity->date);
        $this->assertTrue(($dateActual + 62) > $entity->date->getTimestamp());
    }
}
