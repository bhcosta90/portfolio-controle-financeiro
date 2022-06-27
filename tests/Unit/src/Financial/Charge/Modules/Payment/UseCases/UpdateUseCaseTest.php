<?php

namespace Tests\Unit\src\Financial\Charge\Modules\Payment\UseCases;

use PHPUnit\Framework\TestCase;
use Core\Financial\Charge\Modules\Payment\Domain\PaymentEntity as Entity;
use Core\Financial\Charge\Shared\Enums\ChargeTypeEnum;
use Core\Financial\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Financial\Charge\Modules\Payment\Repository\PaymentRepositoryInterface as Repo;
use Core\Financial\Charge\Modules\Payment\UseCases\UpdateUseCase;
use Core\Financial\Charge\Modules\Payment\UseCases\DTO\Update\{UpdateInput, UpdateOutput};
use Core\Financial\Relationship\Modules\Company\Repository\CompanyRepositoryInterface;
use Mockery;
use Ramsey\Uuid\Uuid;

class UpdateUseCaseTest extends TestCase
{
    public function testHandle()
    {
        $group = Uuid::uuid4();
        $id = Uuid::uuid4();

        $entity = Entity::create(
            $group,
            50,
            CompanyEntity::create('teste', null, null),
            ChargeTypeEnum::CREDIT->value,
            '2022-01-01',
            null,
            1,
            $id,
        );

        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('find')->andReturn($entity);
        $mock->shouldReceive('update')->andReturn(true);
        
        /** @var CompanyRepositoryInterface|Mockery\MockInterface */
        $mockCompany = Mockery::mock(stdClass::class, CompanyRepositoryInterface::class);
        $mockCompany->shouldReceive('find')->andReturn(CompanyEntity::create('bruno costa', null, null));

        /** @var UpdateInput|Mockery\MockInterface */
        $mockInput = $this->getMockClass(originalClassName: UpdateInput::class);

        $uc = new UpdateUseCase(
            repo: $mock,
            company: $mockCompany,
        );

        $handle = $uc->handle(new $mockInput($id, 50, $id, date('Y-m-d'), null));
        $mock->shouldHaveReceived('find')->times(1);
        $mock->shouldHaveReceived('update')->times(1);
        $this->assertInstanceOf(UpdateOutput::class, $handle);
    }
}
