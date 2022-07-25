<?php

namespace Tests;

use Core\Application\BankAccount\Modules\Account\Repository\AccountRepository;
use Core\Application\BankAccount\Modules\Bank\Repository\BankRepository;
use Core\Application\Charge\Modules\Payment\Repository\PaymentRepository;
use Core\Application\Charge\Modules\Receive\Repository\ReceiveRepository;
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository;
use Core\Application\Tenant\Repository\TenantRepository;
use Core\Application\Transaction\Repository\TransactionRepository;
use Core\Shared\Interfaces\EventManagerInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Mockery;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Ramsey\Uuid\Uuid;
use stdClass;
use Throwable;

abstract class UnitCase extends BaseTestCase
{
    protected function id(): string
    {
        return Uuid::uuid4();
    }

    /** @return TransactionInterface|Mockery\MockInterface */
    protected function mockTransaction()
    {
        /** @var Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mock->shouldReceive('commit');
        $mock->shouldReceive('rollback');
        return $mock;
    }

    /** @return RecurrenceRepository|Mockery\MockInterface */
    protected function mockRecurrenceRepository()
    {
        /** @var Mockery\MockInterface */
        return Mockery::mock(stdClass::class, RecurrenceRepository::class);
    }

    /** @return BankRepository|Mockery\MockInterface */
    public function mockBankRepository()
    {
        /** @var Mockery\MockInterface */
        return Mockery::mock(stdClass::class, BankRepository::class);
    }

    /** @return AccountRepository|Mockery\MockInterface */
    public function mockAccountRepository()
    {
        /** @var Mockery\MockInterface */
        return Mockery::mock(stdClass::class, AccountRepository::class);
    }

    /** @return TenantRepository|Mockery\MockInterface */
    public function mockTenantRepository()
    {
        /** @var Mockery\MockInterface */
        return Mockery::mock(stdClass::class, TenantRepository::class);
    }

    /** @return TransactionRepository|Mockery\MockInterface */
    public function mockTransactionRepository()
    {
        /** @var Mockery\MockInterface */
        return Mockery::mock(stdClass::class, TransactionRepository::class);
    }

    /** @return PaymentRepository|Mockery\MockInterface */
    public function mockPaymentRepository()
    {
        /** @var Mockery\MockInterface */
        return Mockery::mock(stdClass::class, PaymentRepository::class);
    }
    
    /** @return ReceiveRepository|Mockery\MockInterface */
    public function mockReceiveRepository()
    {
        /** @var Mockery\MockInterface */
        return Mockery::mock(stdClass::class, ReceiveRepository::class);
    }

    /** @return CompanyRepository|Mockery\MockInterface */
    public function mockCompanyRepository()
    {
        /** @var Mockery\MockInterface */
        return Mockery::mock(stdClass::class, CompanyRepository::class);
    }

    /** @return CustomerRepository|Mockery\MockInterface */
    public function mockCustomerRepository()
    {
        /** @var Mockery\MockInterface */
        return Mockery::mock(stdClass::class, CustomerRepository::class);
    }

    /** @return EventManagerInterface|Mockery\MockInterface */
    public function mockEventManagerInterface()
    {
        /** @var Mockery\MockInterface */
        return Mockery::mock(stdClass::class, EventManagerInterface::class);
    }

    public function mock($fn, array $data)
    {
        foreach ($data as $rs) {
            try {
                /** @var Mockery\MockInterface */
                $mock = $rs['mock'];
                $mock->shouldReceive($rs['action'])->andReturn($rs['return'] ?? null);
            } catch (Throwable $e) {
                dump($rs);
                throw $e;
            }
        }

        $ret = $fn();

        foreach ($data as $rs) {
            /** @var Mockery\MockInterface */
            try {
                $mock = $rs['mock'];
                $mock->shouldHaveReceived($rs['action'])->times($rs['times'] ?? 1);
            } catch (Throwable $e) {
                dump($rs);
                throw $e;
            }
        }

        return $ret;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
    
}
