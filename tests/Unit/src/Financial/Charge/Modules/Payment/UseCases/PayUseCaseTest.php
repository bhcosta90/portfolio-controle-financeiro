<?php

namespace Tests\Unit\src\Financial\Charge\Modules\Payment\UseCases;

use Core\Financial\Charge\Modules\Payment\Domain\PaymentEntity;
use Mockery;
use PHPUnit\Framework\TestCase;
use Core\Financial\Charge\Modules\Payment\UseCases\PayUseCase;
use Core\Financial\Charge\Modules\Payment\UseCases\DTO\Pay\{PayInput, PayOutput};
use Core\Financial\Charge\Modules\Payment\Repository\PaymentRepositoryInterface as Repo;
use Core\Financial\Relationship\Modules\Company\Domain\CompanyEntity;
use Ramsey\Uuid\Uuid;

class PayUseCaseTest extends TestCase
{
    public function testHandle()
    {
        $group = Uuid::uuid4();
        $id = Uuid::uuid4();

        $objEntity = PaymentEntity::create(
            $group,
            50,
            CompanyEntity::create('bruno costa', null, null),
            1,
            '2022-01-01',
            null,
            0,
            null,
            $id,
        );

        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('find')->andReturn($objEntity);
        $mock->shouldReceive('update')->andReturn(true);

        $uc = new PayUseCase(
            repo: $mock,
        );

        $ret = $uc->handle(new PayInput($id, 50, 25));
        $this->assertInstanceOf(PayOutput::class, $ret);
    }
}
