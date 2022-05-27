<?php

namespace Tests\Unit\Costa\Modules\Account\UseCases\Bank;

use Costa\Modules\Account\UseCases\Bank\BankDeleteUseCase as UseCase;
use Costa\Modules\Account\UseCases\Bank\DTO\Find\Input;
use Costa\Shareds\ValueObjects\DeleteObject;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Account\Entities\MockBankEntityTrait;
use Tests\Unit\Costa\Modules\Account\Repository\MockBankRepositoryInterfaceTrait;

class BankDeleteUseCaseTest extends TestCase
{
    use MockBankRepositoryInterfaceTrait, MockBankEntityTrait;

    public function testExec()
    {
        $entity = $this->mockBankEntity();

        $repo = $this->mockBankRepositoryInterface();
        $repo->shouldReceive('delete')->andReturn(true);

        $uc = new UseCase(
            repo: $repo,
        );

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            $entity->id
        ]);
        $resp = $uc->exec($mockInput);

        $this->assertInstanceOf(DeleteObject::class, $resp);
        $this->assertTrue($resp->success);

        $repo->shouldHaveReceived('delete')->times(limit: 1);
    }
}
