<?php

namespace Core\Financial\Charge\Modules\Receive\UseCases;

use Core\Financial\Charge\Modules\Receive\Repository\ReceiveRepositoryInterface;
use Core\Financial\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;
use Core\Shared\UseCases\Delete\DeleteInput;
use Core\Shared\UseCases\Delete\DeleteOutput;

class DeleteUseCase
{
    public function __construct(
        private ReceiveRepositoryInterface $repo,
    ) {
        //
    }

    public function handle(DeleteInput $input): DeleteOutput
    {
        /** @var Entity */
        $obj = $this->repo->find($input->id);
        return new DeleteOutput($this->repo->delete($obj));
    }
}
