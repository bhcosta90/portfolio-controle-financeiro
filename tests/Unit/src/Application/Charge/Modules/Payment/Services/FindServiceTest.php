<?php

namespace Tests\Unit\src\Application\Charge\Modules\Payment\Services;

use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity as Entity;
use Core\Application\Charge\Modules\Payment\Repository\ChargePaymentRepository as ChargeRepo;
use Core\Application\Charge\Modules\Payment\Services\DTO\Find\Output;
use Core\Application\Charge\Modules\Payment\Services\FindService;
use Core\Application\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository;
use Core\Shared\UseCases\Find\FindInput;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class FindServiceTest extends TestCase
{
    public function testHandle()
    {
        $uc = new FindService(
            repository: $mockRepository = $this->mockRepository(),
            relationship: $mockRelationship = $this->mockRelationship(),
        );

        $objEntity = Entity::create(
            tenant: Uuid::uuid4(),
            title: 'teste',
            resume: null,
            company: Uuid::uuid4(),
            recurrence: Uuid::uuid4(),
            value: 50,
            pay: null,
            group: Uuid::uuid4(),
            date: '2022-01-01',
            id: $id = Uuid::uuid4()
        );

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('Find')->andReturn(true);

        /** @var FindInput */
        $mockInput = Mockery::mock(FindInput::class, [$id]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);

        $mockRepository->shouldHaveReceived('find')->times(1);
        $mockRelationship->shouldHaveReceived('find')->times(1);
    }

    private function mockRepository(): string|ChargeRepo|Mockery\MockInterface
    {
        return Mockery::mock(ChargeRepo::class);
    }

    private function mockRelationship(): string|CompanyRepository|Mockery\MockInterface
    {
        /** @var Mockery\MockInterface */
        $mock = Mockery::mock(CompanyRepository::class);
        $mock->shouldReceive('find')->andReturn(CompanyEntity::create(Uuid::uuid4(), 'teste'));
        return $mock;
    }
}
