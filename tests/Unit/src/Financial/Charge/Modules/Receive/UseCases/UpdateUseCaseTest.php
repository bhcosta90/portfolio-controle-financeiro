<?php

namespace Tests\Unit\src\Financial\Charge\Modules\Receive\UseCases;

use PHPUnit\Framework\TestCase;
use Core\Financial\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;
use Core\Financial\Charge\Shared\Enums\ChargeTypeEnum;
use Core\Financial\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Financial\Charge\Modules\Receive\Repository\ReceiveRepositoryInterface as Repo;
use Core\Financial\Charge\Modules\Receive\UseCases\UpdateUseCase;
use Core\Financial\Charge\Modules\Receive\UseCases\DTO\Update\{UpdateInput, UpdateOutput};
use Core\Financial\Relationship\Modules\Customer\Repository\CustomerRepositoryInterface;
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
            CustomerEntity::create('teste', null, null),
            ChargeTypeEnum::CREDIT->value,
            '2022-01-01',
            null,
            0,
            1,
            $id,
        );

        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('find')->andReturn($entity);
        $mock->shouldReceive('update')->andReturn(true);
        
        /** @var CustomerRepositoryInterface|Mockery\MockInterface */
        $mockCustomer = Mockery::mock(stdClass::class, CustomerRepositoryInterface::class);
        $mockCustomer->shouldReceive('find')->andReturn(CustomerEntity::create('bruno costa', null, null));

        /** @var UpdateInput|Mockery\MockInterface */
        $mockInput = $this->getMockClass(originalClassName: UpdateInput::class);

        $uc = new UpdateUseCase(
            repo: $mock,
            customer: $mockCustomer,
        );

        $handle = $uc->handle(new $mockInput($id, 50, $id, date('Y-m-d'), null));
        $mock->shouldHaveReceived('find')->times(1);
        $mock->shouldHaveReceived('update')->times(1);
        $this->assertInstanceOf(UpdateOutput::class, $handle);
    }
}
