<?php

namespace Costa\Modules\Charge\UseCases\Charge;

abstract class ChargeFindUseCase
{
    public function exec(DTO\Find\Input $input): DTO\Find\Output
    {
        $obj = $this->repo->find($input->id);

        return new DTO\Find\Output(
            title: $obj->title->value,
            description: $obj->description->value,
            relationship: $obj->relationship,
            value: $obj->value->value,
            payValue: $obj->payValue->value,
            date: $obj->date,
            recurrence: $obj->recurrence,
            id: $obj->id(),
            createdAt: $obj->createdAt,
        );
    }
}
