<?php

namespace Tests\Unit\src\Financial\Charge\Modules\Payment\UseCases;

use Core\Financial\Charge\Modules\Payment\UseCases\CreateUseCase;
use Core\Financial\Charge\Modules\Payment\UseCases\DTO\Create\{CreateInput, CreateOutput};
use Core\Financial\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Financial\Charge\Modules\Payment\Repository\PaymentRepositoryInterface as Repo;
use Core\Financial\Relationship\Modules\Company\Repository\CompanyRepositoryInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Mockery;
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

        /** @var CompanyRepositoryInterface|Mockery\MockInterface */
        $mockCompany = Mockery::mock(stdClass::class, CompanyRepositoryInterface::class);
        $mockCompany->shouldReceive('find')->andReturn(CompanyEntity::create('bruno costa', null, null));

        /** @var CreateInput|Mockery\MockInterface */
        $mockInput = $this->getMockClass(originalClassName: CreateInput::class);

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');

        $uc = new CreateUseCase(
            repo: $mock,
            company: $mockCompany,
            transaction: $mockTransaction,
        );

        $handle = $uc->handle(new $mockInput($group, 50, $id));
        $mock->shouldHaveReceived('insert')->times(1);
        $mockCompany->shouldHaveReceived('find')->times(1);
        $this->assertInstanceOf(CreateOutput::class, $handle[0]);
    }
}
