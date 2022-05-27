<?php

namespace Tests\Unit\Costa\Modules\Account\UseCases\Bank;

use Costa\Modules\Account\UseCases\Bank\BankUpdateUseCase as UseCase;
use Costa\Modules\Account\UseCases\Bank\DTO\Update\Input;
use Costa\Modules\Account\UseCases\Bank\DTO\Update\Output;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Account\Entities\MockAccountEntityTrait;
use Tests\Unit\Costa\Modules\Account\Entities\MockBankEntityTrait;
use Tests\Unit\Costa\Modules\Account\Repository\MockAccountRepositoryInterfaceTrait;
use Tests\Unit\Costa\Modules\Account\Repository\MockBankRepositoryInterfaceTrait;
use Tests\Unit\Costa\Shareds\Contracts\MockTransactionContractTrait;

class BankUpdateUseCaseTest extends TestCase
{
    use MockBankRepositoryInterfaceTrait, MockTransactionContractTrait, MockBankEntityTrait;
    use MockAccountRepositoryInterfaceTrait, MockAccountEntityTrait;

    public function testExec()
    {
        $entity = $this->mockBankEntity();

        $entityEdit = $this->mockBankEntity(name: 'bruno costa', id: $entity->id);
        $entity->shouldReceive('id')->andReturn((string) $entity->id);
        $entity->shouldReceive('update')->andReturn($entity);

        $repo = $this->mockBankRepositoryInterface();
        $repo->shouldReceive('find')->andReturn($entity);
        $repo->shouldReceive('update')->andReturn($entityEdit);

        $account = $this->mockAccountRepositoryInterface();
        $account->shouldReceive('find')->andReturn($this->mockAccountEntity());

        $uc = new UseCase(
            repo: $repo,
            account: $account,
            transaction: $this->mockTransactionContract(),
        );

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            $entity->id,
            'bruno costa',
            true,
            0,
        ]);
        $resp = $uc->exec($mockInput);

        $this->assertInstanceOf(Output::class, $resp);
        $this->assertEquals($entity->id, $resp->id);
        $repo->shouldHaveReceived('update')->times(limit: 1);
        $account->shouldNotHaveReceived('update');
    }

    public function testExecUpdateValue()
    {
        $entity = $this->mockBankEntity();
        $entityEdit = $this->mockBankEntity(name: 'bruno costa', id: $entity->id);

        $accountEntity = $this->mockAccountEntity();
        $accountEntity->shouldReceive('update')->andReturn($accountEntity);

        $entity->shouldReceive('id')->andReturn((string) $entity->id);
        $entity->shouldReceive('update')->andReturn($entity);

        $repo = $this->mockBankRepositoryInterface();
        $repo->shouldReceive('find')->andReturn($entity);
        $repo->shouldReceive('update')->andReturn($entityEdit);

        $account = $this->mockAccountRepositoryInterface();
        $account->shouldReceive('find')->andReturn($accountEntity);
        $account->shouldReceive('update')->andReturn($accountEntity);

        $uc = new UseCase(
            repo: $repo,
            account: $account,
            transaction: $this->mockTransactionContract(),
        );

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            $entity->id,
            'bruno costa',
            50,
            true
        ]);
        $resp = $uc->exec($mockInput);

        $this->assertInstanceOf(Output::class, $resp);
        $this->assertEquals($entity->id, $resp->id);
        $repo->shouldHaveReceived('update')->times(limit: 1);
        $account->shouldHaveReceived('update')->times(limit: 1);
    }
}
