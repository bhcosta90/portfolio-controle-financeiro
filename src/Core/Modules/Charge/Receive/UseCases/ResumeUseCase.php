<?php

namespace Costa\Modules\Charge\Receive\UseCases;

use Costa\Modules\Charge\Receive\Repository\ChargeRepositoryInterface;

class ResumeUseCase
{
    public function __construct(
        private ChargeRepositoryInterface $repo
    ) {
        //
    }
    
    public function handle(DTO\Resume\Input $input): DTO\Resume\Output
    {
        $type = str_replace(' ', '', 'getResume ' . ucwords(str_replace('-', ' ', $input->type)));
        $obj = $this->repo->$type($input->date);
        return new DTO\Resume\Output(quantity: $obj->quantity, total: $obj->total);
    }
}
