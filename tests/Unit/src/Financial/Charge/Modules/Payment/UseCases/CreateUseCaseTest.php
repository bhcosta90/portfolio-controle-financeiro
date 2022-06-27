<?php

namespace Tests\Unit\src\Financial\Charge\Modules\Payment\UseCases;

use Core\Financial\Charge\Modules\Payment\UseCases\CreateUseCase;
use Core\Financial\Charge\Modules\Payment\UseCases\DTO\Create\{CreateInput, CreateOutput};
use Core\Financial\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Financial\Charge\Modules\Payment\Repository\PaymentRepositoryInterface as Repo;
use Core\Financial\Relationship\Modules\Company\Repository\CompanyRepositoryInterface;
use Core\Financial\Recurrence\Repository\RecurrenceRepositoryInterface;
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

        /** @var RecurrenceRepositoryInterface|Mockery\MockInterface */
        $mockRecurrence = Mockery::mock(stdClass::class, RecurrenceRepositoryInterface::class);

        /** @var CreateInput|Mockery\MockInterface */
        $mockInput = $this->getMockClass(originalClassName: CreateInput::class);

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');

        $uc = new CreateUseCase(
            repo: $mock,
            company: $mockCompany,
            recurrence: $mockRecurrence,
            transaction: $mockTransaction,
        );

        $handle = $uc->handle(new $mockInput($group, 50, $id, '2022-06-27', null));
        $mock->shouldHaveReceived('insert')->times(1);
        $mockCompany->shouldHaveReceived('find')->times(1);
        $this->assertInstanceOf(CreateOutput::class, $handle[0]);
    }

    public function testHandleWithParcel() {
        $id = Uuid::uuid4();
        $group = Uuid::uuid4();

        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('insert')->andReturn(true);

        /** @var CompanyRepositoryInterface|Mockery\MockInterface */
        $mockCompany = Mockery::mock(stdClass::class, CompanyRepositoryInterface::class);
        $mockCompany->shouldReceive('find')->andReturn(CompanyEntity::create('bruno costa', null, null));

        /** @var RecurrenceRepositoryInterface|Mockery\MockInterface */
        $mockRecurrence = Mockery::mock(stdClass::class, RecurrenceRepositoryInterface::class);

        /** @var CreateInput|Mockery\MockInterface */
        $mockInput = $this->getMockClass(originalClassName: CreateInput::class);

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');

        $uc = new CreateUseCase(
            repo: $mock,
            company: $mockCompany,
            recurrence: $mockRecurrence,
            transaction: $mockTransaction,
        );

        $handle = $uc->handle(new $mockInput($group, 50, $id, '2022-06-27', null, 7));
        $mockCompany->shouldHaveReceived('find')->times(1);
        $mock->shouldHaveReceived('insert')->times(7);
        $this->assertInstanceOf(CreateOutput::class, $handle[0]);
        $this->assertEquals('2022-06-27', $handle[0]->date);
        $this->assertEquals(7.14, $handle[0]->value);
        
        $this->assertEquals('2022-12-27', $handle[6]->date);
        $this->assertEquals(7.16, $handle[6]->value);
    }

    public function testHandleWithParcelLastDayMonth() {
        $id = Uuid::uuid4();
        $group = Uuid::uuid4();

        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('insert')->andReturn(true);

        /** @var CompanyRepositoryInterface|Mockery\MockInterface */
        $mockCompany = Mockery::mock(stdClass::class, CompanyRepositoryInterface::class);
        $mockCompany->shouldReceive('find')->andReturn(CompanyEntity::create('bruno costa', null, null));

        /** @var RecurrenceRepositoryInterface|Mockery\MockInterface */
        $mockRecurrence = Mockery::mock(stdClass::class, RecurrenceRepositoryInterface::class);

        /** @var CreateInput|Mockery\MockInterface */
        $mockInput = $this->getMockClass(originalClassName: CreateInput::class);

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');

        $uc = new CreateUseCase(
            repo: $mock,
            company: $mockCompany,
            recurrence: $mockRecurrence,
            transaction: $mockTransaction,
        );

        $handle = $uc->handle(new $mockInput($group, 50, $id, '2022-05-31', null, 7));
        $mockCompany->shouldHaveReceived('find')->times(1);
        $mock->shouldHaveReceived('insert')->times(7);
        $this->assertInstanceOf(CreateOutput::class, $handle[0]);
        $this->assertEquals('2022-05-31', $handle[0]->date);
        $this->assertEquals('2022-06-30', $handle[1]->date);
        $this->assertEquals('2022-07-31', $handle[2]->date);
    }
}
