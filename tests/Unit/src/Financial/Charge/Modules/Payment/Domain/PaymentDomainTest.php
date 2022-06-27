<?php

namespace Tests\Unit\src\Financial\Charge\Modules\Payment\Domain;

use Core\Financial\Charge\Modules\Payment\Domain\PaymentEntity as Entity;
use Core\Financial\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Shared\ValueObjects\UuidObject;
use PHPUnit\Framework\TestCase;

class PaymentDomainTest extends TestCase
{
    public function testCreate()
    {
        $obj = $this->getEntity();
        $this->assertNotEmpty($obj->id());
        $this->assertNotEmpty($obj->createdAt());
    }

    public function testUpdate()
    {
        $company = CompanyEntity::create('bruno costa 1234', null, null);
        $obj = $this->getEntity();
        $companyOld = $obj->company;
        $obj->update(50, $company);
        $this->assertEquals('bruno costa 1234', $obj->company->name->value);
        $this->assertEquals(50, $obj->value);
        $this->assertNotEquals($companyOld->id(), $obj->company->id());
    }

    private function getEntity(
        $group = null,
        $value = 0.01,
        $company = null,
        int $type = 1,
        $createdAt = null,
    ) {
        if (empty($company)) {
            $company = CompanyEntity::create('bruno costa', null, null);
        }

        return Entity::create(
            group: $group ? new UuidObject($group) : UuidObject::random(),
            value: $value,
            company: $company,
            type: $type,
            createdAt: $createdAt,
        );
    }
}
