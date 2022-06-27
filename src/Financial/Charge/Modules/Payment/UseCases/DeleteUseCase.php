<?php

namespace Core\Financial\Charge\Modules\Payment\UseCases;

use Core\Financial\Charge\Modules\Payment\Repository\PaymentRepositoryInterface;
use Core\Financial\Charge\Modules\Payment\Domain\PaymentEntity as Entity;
use Core\Shared\UseCases\Delete\DeleteInput;
use Core\Shared\UseCases\Delete\DeleteOutput;

class DeleteUseCase
{
    public function __construct(
        private PaymentRepositoryInterface $repo,
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
