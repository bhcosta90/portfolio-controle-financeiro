<?php

namespace Tests\Unit\src\Application\Charge\Modules\Payment\UseCases;

use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity;
use Core\Application\Charge\Modules\Payment\UseCases\PaymentUseCase;
use Core\Application\Charge\Modules\Payment\UseCases\DTO\Payment\{Input, Output};
use Core\Application\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Application\Tenant\Domain\TenantEntity;
use Tests\UnitCase as TestCase;

class PaymentUseCaseTest extends TestCase
{
    protected PaymentUseCase $uc;
    protected $mockPaymentRepository;
    protected $mockCompanyRepository;
    protected $mockTenantRepository;
    protected $mockTransactionRepository;
    protected $mockEventManagerInterface;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uc = new PaymentUseCase(
            $this->mockPaymentRepository = $this->mockPaymentRepository(),
            $this->mockCompanyRepository = $this->mockCompanyRepository(),
            $this->mockTenantRepository = $this->mockTenantRepository(),
            $this->mockTransactionRepository = $this->mockTransactionRepository(),
            $this->mockEventManagerInterface = $this->mockEventManagerInterface(),
            $this->mockTransaction(),
            $this->mockBankRepository(),
        );
    }

    public function testCreate()
    {
        $ret = $this->mock(fn () => $this->uc->handle(new Input($this->id(), 50, date('Y-m-d'), null)), [
            [
                'mock' => $this->mockPaymentRepository,
                'action' => 'find',
                'return' => PaymentEntity::create(
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
                'mock' => $this->mockPaymentRepository,
                'action' => 'update',
                'return' => true,
            ],
            [
                'mock' => $this->mockCompanyRepository,
                'action' => 'find',
                'return' => CompanyEntity::create($tenant, 'test', 0, $this->id()),
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
        $ret = $this->mock(fn () => $this->uc->handle(
            new Input(
                $this->id(),
                35,
                date('Y-m-d'),
                null,
                true,
                date('Y-m-d', strtotime('+10 days'))
            )
        ), [
            [
                'mock' => $this->mockPaymentRepository,
                'action' => 'find',
                'return' => PaymentEntity::create(
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
                'mock' => $this->mockPaymentRepository,
                'action' => 'update',
                'return' => true,
            ],
            [
                'mock' => $this->mockCompanyRepository,
                'action' => 'find',
                'return' => CompanyEntity::create($tenant, 'test', 0, $this->id()),
                'times' => 1,
            ],
            [
                'mock' => $this->mockPaymentRepository,
                'action' => 'insert',
                'return' => true,
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
}
