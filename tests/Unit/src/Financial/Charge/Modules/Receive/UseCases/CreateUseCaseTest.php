<?php

namespace Tests\Unit\src\Financial\Charge\Modules\Receive\UseCases;

use Mockery;
use Core\Financial\Charge\Modules\Receive\Repository\ReceiveRepositoryInterface as Repo;
use Core\Financial\Relationship\Modules\Customer\Repository\CustomerRepositoryInterface;
use Core\Financial\Charge\Modules\Receive\UseCases\CreateUseCase;
use Core\Financial\Charge\Modules\Receive\UseCases\DTO\Create\{CreateInput, CreateOutput};
use Core\Financial\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Shared\Interfaces\TransactionInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CreateUseCaseTest extends TestCase
{
    public function testHandle()
    {
        $id = Uuid::uuid4();
        $group = Uuid::uuid4();

        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('insert')->andReturn(true);

        /** @var CustomerRepositoryInterface|Mockery\MockInterface */
        $mockCustomer = Mockery::mock(stdClass::class, CustomerRepositoryInterface::class);
        $mockCustomer->shouldReceive('find')->andReturn(CustomerEntity::create('bruno costa', null, null));

        /** @var CreateInput|Mockery\MockInterface */
        $mockInput = $this->getMockClass(originalClassName: CreateInput::class);

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');

        $uc = new CreateUseCase(
            repo: $mock,
            customer: $mockCustomer,
            transaction: $mockTransaction,
        );

        $handle = $uc->handle(new $mockInput($group, 50, $id));
        $mock->shouldHaveReceived('insert')->times(1);
        $mockCustomer->shouldHaveReceived('find')->times(1);
        $this->assertInstanceOf(CreateOutput::class, $handle[0]);
    }
}
