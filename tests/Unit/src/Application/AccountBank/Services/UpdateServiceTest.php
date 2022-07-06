<?php

namespace Tests\Unit\src\Application\AccountBank\Services;

use Core\Application\AccountBank\Repository\AccountBankRepository;
use Core\Application\AccountBank\Services\UpdateService;
use Core\Application\AccountBank\Services\DTO\Update\{Input, Output};
use Core\Application\Payment\Repository\PaymentRepository;
use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UpdateServiceTest extends TestCase
{
    public function testHandle()
    {
        $uc = new UpdateService(
            repository: $mockRepository = $this->mockRepository(),
            payment: $mockPayment = $this->mockPaymentRepository(),
        );
        $objEntity = CustomerEntity::create(tenant: Uuid::uuid4(), name:  'test', id: $id = Uuid::uuid4());

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('update')->andReturn(true);
        $mockPayment->shouldReceive('insert')->andReturn(true);

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [$id, 'test', -10]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);

        $mockRepository->shouldHaveReceived('find')->times(1);
        $mockRepository->shouldHaveReceived('update')->times(1);
        $mockPayment->shouldHaveReceived('insert')->times(1);
    }

    private function mockRepository(): string|AccountBankRepository|Mockery\MockInterface
    {
        return Mockery::mock(AccountBankRepository::class);
    }

    private function mockPaymentRepository(): string|PaymentRepository|Mockery\MockInterface
    {
        return Mockery::mock(PaymentRepository::class);
    }
}
