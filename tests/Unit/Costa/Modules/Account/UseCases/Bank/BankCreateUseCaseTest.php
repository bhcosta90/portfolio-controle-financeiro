<?php

namespace Tests\Unit\Costa\Modules\Account\UseCases\Bank;

use PHPUnit\Framework\TestCase;
use Costa\Modules\Account\UseCases\Bank\BankCreateUseCase as UseCase;
use Costa\Modules\Account\UseCases\Bank\DTO\Create\{Input, Output};
use Mockery;
use Tests\Unit\Costa\Modules\Account\Entities\MockAccountEntityTrait;
use Tests\Unit\Costa\Modules\Account\Entities\MockBankEntityTrait;
use Tests\Unit\Costa\Modules\Account\Repository\MockAccountRepositoryInterfaceTrait;
use Tests\Unit\Costa\Modules\Account\Repository\MockBankRepositoryInterfaceTrait;
use Tests\Unit\Costa\Shareds\Contracts\MockTransactionContractTrait;

class BankCreateUseCaseTest extends TestCase
{
    use MockBankRepositoryInterfaceTrait, MockAccountRepositoryInterfaceTrait, MockTransactionContractTrait;
    use MockBankEntityTrait, MockAccountEntityTrait;

    public function testExec()
    {
        $repo = $this->mockBankRepositoryInterface();
        $repo->shouldReceive('insert')->andReturn($this->mockBankEntity());

        $account = $this->mockAccountRepositoryInterface();
        $account->shouldReceive('insert')->andReturn($this->mockAccountEntity(value: 50));

        $transaction = $this->mockTransactionContract();

        $uc = new UseCase(
            repo: $repo,
            account: $account,
            transaction: $transaction,
        );

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            'bruno costa',
            true
        ]);
        $resp = $uc->exec($mockInput);

        $this->assertInstanceOf(Output::class, $resp);
        $this->assertEquals('bruno costa', $resp->name);
        $this->assertNotEmpty($resp->id);        

        $repo->shouldHaveReceived('insert')->times(limit: 1);
        $account->shouldHaveReceived('insert')->times(limit: 1);
        $transaction->shouldHaveReceived('commit')->times(limit: 1);
    }
}
