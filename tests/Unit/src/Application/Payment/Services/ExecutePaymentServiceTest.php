<?php

namespace Tests\Unit\src\Application\Payment\Services;

use App\Models\Payment;
use Core\Application\AccountBank\Domain\AccountBankEntity;
use Core\Application\AccountBank\Repository\AccountBankRepository;
use Core\Application\Payment\Domain\PaymentEntity;
use Core\Application\Payment\Repository\PaymentRepository;
use Core\Application\Payment\Services\ExecutePaymentService;
use Core\Application\Payment\Services\DTO\ExecutePayment\{Input, Output};
use Core\Application\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository;
use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository;
use Core\Shared\Interfaces\ResultInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\ValueObjects\EntityObject;
use DateTime;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ExecutePaymentServiceTest extends TestCase
{
    public function testSimple()
    {
        $uc = new ExecutePaymentService(
            repository: $mockRepository = $this->mockPaymentRepository(),
            customer: $this->mockCustomerRepository(),
            company: $this->mockCompanyRepository(),
            account: $this->mockAccountBankRepository(),
            transaction: $this->mockTransactionInterface(),
        );

        $mockResultInterface = $this->mockResultInterface();

        $mockRepository->shouldReceive('updateStatus');
        $mockRepository->shouldReceive('getListStatus')->andReturn($mockResultInterface);

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [new DateTime('2022-01-10')]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);

        $mockRepository->shouldHaveReceived('updateStatus')->times(1);
        $mockRepository->shouldHaveReceived('getListStatus')->times(1);
        $mockRepository->shouldNotHaveReceived('update');
    }

    public function testExecuteOnlyPaymentCustomer()
    {
        $objRelationship = CustomerEntity::create(Uuid::uuid4(), 'name');

        $uc = new ExecutePaymentService(
            repository: $mockRepository = $this->mockPaymentRepository(),
            customer: $mockCustomer = $this->mockCustomerRepository(),
            company: $this->mockCompanyRepository(),
            account: $this->mockAccountBankRepository(),
            transaction: $this->mockTransactionInterface(),
        );

        $entity = PaymentEntity::create(
            relationship: new EntityObject($objRelationship->id(), $objRelationship),
            charge: new EntityObject(1, 'teste'),
            bank: null,
            value: 50,
            status: 1,
            type: 1,
            date: date('Y-m-d'),
            title: 'teste',
            resume: null,
            name: 'teste',
        );

        $mockResultInterface = $this->mockResultInterface([
            $entity
        ]);

        $mockCustomer->shouldReceive('find')->andReturn($objRelationship);
        $mockCustomer->shouldReceive('update')->andReturn(true);
        $mockRepository->shouldReceive('updateStatus');
        $mockRepository->shouldReceive('update')->andReturn(true);
        $mockRepository->shouldReceive('entity')->andReturn($entity);
        $mockRepository->shouldReceive('getListStatus')->andReturn($mockResultInterface);

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [new DateTime('2022-01-10')]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);

        $mockRepository->shouldHaveReceived('updateStatus')->times(1);
        $mockRepository->shouldHaveReceived('getListStatus')->times(1);
        $mockRepository->shouldHaveReceived('update')->times(1);
        $mockCustomer->shouldHaveReceived('find')->times(1);
        $mockCustomer->shouldHaveReceived('update')->times(1);
    }

    public function testExecuteOnlyPaymentCompany()
    {
        $objRelationship = CompanyEntity::create(Uuid::uuid4(), 'name');

        $uc = new ExecutePaymentService(
            repository: $mockRepository = $this->mockPaymentRepository(),
            customer: $this->mockCustomerRepository(),
            company: $mockCompany = $this->mockCompanyRepository(),
            account: $this->mockAccountBankRepository(),
            transaction: $this->mockTransactionInterface(),
        );

        $entity = PaymentEntity::create(
            relationship: new EntityObject(1, CompanyEntity::class),
            charge: new EntityObject($objRelationship->id(), $objRelationship),
            bank: null,
            value: 50,
            status: 1,
            type: 1,
            date: date('Y-m-d'),
            title: 'teste',
            resume: null,
            name: 'teste',
        );

        $mockResultInterface = $this->mockResultInterface([
            $entity
        ]);

        $mockRepository->shouldReceive('updateStatus');
        $mockRepository->shouldReceive('update')->andReturn(true);
        $mockCompany->shouldReceive('find')->andReturn($objRelationship);
        $mockCompany->shouldReceive('update')->andReturn(true);
        $mockRepository->shouldReceive('entity')->andReturn($entity);
        $mockRepository->shouldReceive('getListStatus')->andReturn($mockResultInterface);

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [new DateTime('2022-01-10')]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);

        $mockRepository->shouldHaveReceived('updateStatus')->times(1);
        $mockRepository->shouldHaveReceived('getListStatus')->times(1);
        $mockRepository->shouldHaveReceived('update')->times(1);
        $mockCompany->shouldHaveReceived('find')->times(1);
        $mockCompany->shouldHaveReceived('update')->times(1);
    }

    public function testExecuteOnlyPaymentBank()
    {
        $objRelationship = CompanyEntity::create(Uuid::uuid4(), 'name');
        $objAccount = AccountBankEntity::create(Uuid::uuid4(), 'teste', 0);

        $uc = new ExecutePaymentService(
            repository: $mockRepository = $this->mockPaymentRepository(),
            customer: $this->mockCustomerRepository(),
            company: $mockCompany = $this->mockCompanyRepository(),
            account: $mockAccountBank = $this->mockAccountBankRepository(),
            transaction: $this->mockTransactionInterface(),
        );

        $entity = PaymentEntity::create(
            relationship: new EntityObject(1, CompanyEntity::class),
            charge: new EntityObject($objRelationship->id(), $objRelationship),
            bank: $objAccount->id(),
            value: 50,
            status: 1,
            type: 1,
            date: date('Y-m-d'),
            title: 'teste',
            resume: null,
            name: 'teste',
        );

        $mockResultInterface = $this->mockResultInterface([
            $entity
        ]);

        $mockRepository->shouldReceive('updateStatus');
        $mockRepository->shouldReceive('update')->andReturn(true);
        $mockCompany->shouldReceive('find')->andReturn($objRelationship);
        $mockAccountBank->shouldReceive('find')->andReturn($objAccount);
        $mockCompany->shouldReceive('update')->andReturn(true);
        $mockAccountBank->shouldReceive('update')->andReturn(true);
        $mockRepository->shouldReceive('entity')->andReturn($entity);
        $mockRepository->shouldReceive('getListStatus')->andReturn($mockResultInterface);

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [new DateTime('2022-01-10')]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);

        $mockRepository->shouldHaveReceived('updateStatus')->times(1);
        $mockRepository->shouldHaveReceived('getListStatus')->times(1);
        $mockRepository->shouldHaveReceived('update')->times(1);
        $mockCompany->shouldHaveReceived('find')->times(1);
        $mockCompany->shouldHaveReceived('update')->times(1);
        $mockAccountBank->shouldHaveReceived('find')->times(1);
        $mockAccountBank->shouldHaveReceived('update')->times(1);
    }

    private function mockAccountBankRepository(): string|AccountBankRepository|Mockery\MockInterface
    {
        return Mockery::mock(AccountBankRepository::class);
    }

    private function mockPaymentRepository(): string|PaymentRepository|Mockery\MockInterface
    {
        return Mockery::mock(PaymentRepository::class);
    }

    private function mockCompanyRepository(): string|CompanyRepository|Mockery\MockInterface
    {
        return Mockery::mock(CompanyRepository::class);
    }

    private function mockCustomerRepository(): string|CustomerRepository|Mockery\MockInterface
    {
        return Mockery::mock(CustomerRepository::class);
    }

    private function mockTransactionInterface(): string|TransactionInterface|Mockery\MockInterface
    {
        /** @var Mockery\MockInterface */
        $mock = Mockery::mock(TransactionInterface::class);
        $mock->shouldReceive('commit');
        $mock->shouldReceive('rollback');
        return $mock;
    }

    private function mockResultInterface($entity = []): string|ResultInterface|Mockery\MockInterface
    {
        /** @var Mockery\MockInterface */
        $mock = Mockery::mock(ResultInterface::class);
        $mock->shouldReceive('items')->andReturn($entity);
        return $mock;
    }
}
