<?php

namespace Tests\Unit\Costa\Modules\Account\UseCases\Bank;

use Costa\Modules\Account\UseCases\Bank\BankFindUseCase as UseCase;
use Costa\Modules\Account\UseCases\Bank\DTO\Find\Input;
use Costa\Modules\Account\UseCases\Bank\DTO\Find\Output;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Account\Entities\MockAccountEntityTrait;
use Tests\Unit\Costa\Modules\Account\Entities\MockBankEntityTrait;
use Tests\Unit\Costa\Modules\Account\Repository\MockAccountRepositoryInterfaceTrait;
use Tests\Unit\Costa\Modules\Account\Repository\MockBankRepositoryInterfaceTrait;

class BankFindUseCaseTest extends TestCase
{
    use MockBankRepositoryInterfaceTrait, MockBankEntityTrait, MockAccountRepositoryInterfaceTrait, MockAccountEntityTrait;

    public function testExec()
    {
        $entity = $this->mockBankEntity();
        $entity->shouldReceive('id')->andReturn((string) $entity->id);

        $repo = $this->mockBankRepositoryInterface();
        $repo->shouldReceive('find')->andReturn($entity);

        $account = $this->mockAccountRepositoryInterface();
        $account->shouldReceive('find')->andReturn($this->mockAccountEntity());

        $uc = new UseCase(
            repo: $repo,
            account: $account,
        );

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            $entity->id
        ]);
        $resp = $uc->exec($mockInput);

        $this->assertInstanceOf(Output::class, $resp);
        $this->assertEquals('bank in test', $resp->name);
        $this->assertNotEmpty($resp->id);        

        $repo->shouldHaveReceived('find')->times(limit: 1);
    }
}
