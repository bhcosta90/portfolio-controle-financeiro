<?php

namespace Costa\Modules\Charge\UseCases\Charge;

class ChargeResumeUseCase
{
    public function exec(DTO\Resume\Input $input): DTO\Resume\Output
    {
        $type = str_replace(' ', '', 'getResume ' . ucwords(str_replace('-', ' ', $input->type)));
        $obj = $this->repo->$type($input->date);
        return new DTO\Resume\Output(quantity: $obj->quantity, total: $obj->total);
    }
}