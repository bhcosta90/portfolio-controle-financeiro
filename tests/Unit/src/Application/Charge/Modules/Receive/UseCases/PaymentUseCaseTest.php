<?php

namespace Tests\Unit\src\Application\Charge\Modules\Receive\UseCases;

use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity;
use Core\Application\Charge\Modules\Receive\UseCases\PaymentUseCase;
use Core\Application\Charge\Modules\Receive\UseCases\DTO\Payment\{Input, Output};
use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Application\Tenant\Domain\TenantEntity;
use Tests\UnitCase as TestCase;

class PaymentUseCaseTest extends TestCase
{
    protected PaymentUseCase $uc;
    protected $mockReceiveRepository;
    protected $mockCustomerRepository;
    protected $mockTenantRepository;
    protected $mockTransactionRepository;
    protected $mockEventManagerInterface;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uc = new PaymentUseCase(
            $this->mockReceiveRepository = $this->mockReceiveRepository(),
            $this->mockCustomerRepository = $this->mockCustomerRepository(),
            $this->mockTenantRepository = $this->mockTenantRepository(),
            $this->mockTransactionRepository = $this->mockTransactionRepository(),
            $this->mockEventManagerInterface = $this->mockEventManagerInterface(),
            $this->mockTransaction(),
            $mockBankRepository = $this->mockBankRepository(),
        );
    }

    public function testCreate()
    {
        $ret = $this->mock(fn () => $this->uc->handle(new Input($this->id(), 50, date('Y-m-d'), null)), [
            [
                'mock' => $this->mockReceiveRepository,
                'action' => 'find',
                'return' => ReceiveEntity::create(
                    $tenant = $this->id(),
                    "test",
                    "test",
                    $this->id(),
                    null,
                    50,
                    null,
                    $this->id(),
                    date('Y-m-d'),
                    null,
                    null
                ),
                'times' => 1,
            ],
            [
                'mock' => $this->mockReceiveRepository,
                'action' => 'update',
                'return' => true,
            ],
            [
                'mock' => $this->mockCustomerRepository,
                'action' => 'find',
                'return' => CustomerEntity::create($tenant, 'test', 0, $this->id()),
                'times' => 1,
            ],
            [
                'mock' => $this->mockTenantRepository,
                'action' => 'find',
                'return' => TenantEntity::create(0, $tenant, $tenant),
                'times' => 1,
            ],
            [
                'mock' => $this->mockTransactionRepository,
                'action' => 'insert',
                'return' => true,
                'times' => 2,
            ],
            [
                'mock' => $this->mockEventManagerInterface,
                'action' => 'dispatch',
                'return' => null,
                'times' => 1,
            ]
        ]);

        $this->assertInstanceOf(Output::class, $ret);
        $this->assertTrue($ret->success);
    }

    public function testCreateWithNewCharge()
    {
        $ret = $this->mock(fn () => $this->uc->handle(new Input(
            $this->id(),
            30,
            date('Y-m-d'),
            null,
            true,
            date('Y-m-d', strtotime('+10 days'))
        )), [
            [
                'mock' => $this->mockReceiveRepository,
                'action' => 'find',
                'return' => ReceiveEntity::create(
                    $tenant = $this->id(),
                    "test",
                    "test",
                    $this->id(),
                    null,
                    50,
                    null,
                    $this->id(),
                    date('Y-m-d'),
                    null,
                    null
                ),
                'times' => 1,
            ],
            [
                'mock' => $this->mockReceiveRepository,
                'action' => 'update',
                'return' => true,
            ],
            [
                'mock' => $this->mockCustomerRepository,
                'action' => 'find',
                'return' => CustomerEntity::create($tenant, 'test', 0, $this->id()),
                'times' => 1,
            ],
            [
                'mock' => $this->mockTenantRepository,
                'action' => 'find',
                'return' => TenantEntity::create(0, $tenant, $tenant),
                'times' => 1,
            ],
            [
                'mock' => $this->mockReceiveRepository,
                'action' => 'insert',
                'return' => true,
                'times' => 1,
            ],
            [
                'mock' => $this->mockTransactionRepository,
                'action' => 'insert',
                'return' => true,
                'times' => 2,
            ],
            [
                'mock' => $this->mockEventManagerInterface,
                'action' => 'dispatch',
                'return' => null,
                'times' => 1,
            ]
        ]);

        $this->assertInstanceOf(Output::class, $ret);
        $this->assertTrue($ret->success);
    }
}
