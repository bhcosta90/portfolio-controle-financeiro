<?php

namespace Costa\Modules\Charge\UseCases\Charge;

use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\Input\InputValueObject;
use Costa\Shareds\ValueObjects\ModelObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;

class ChargeUpdateUseCase
{
    public function exec(DTO\Update\Input $input): DTO\Update\Output
    {
        $objCustomer = $this->relationship->find($input->relationship->id);
        $idRecurrence = $input->recurrence ? $this->recurrence->find($input->recurrence)->id : null;

        $obj = $this->repo->find(id: $input->id);

        $obj->update(
            title: new InputNameObject($input->title),
            description: new InputNameObject($input->description, true),
            relationship: new ModelObject($objCustomer->id, $objCustomer),
            value: new InputValueObject($input->value),
            date: $input->date,
            recurrence: $idRecurrence,
        );

        $this->repo->update($obj);

        return new DTO\Update\Output(
            title: $obj->title->value,
            description: $obj->description->value,
            relationship: $obj->relationship,
            value: $obj->value->value,
            date: $obj->date,
            recurrence: $obj->recurrence,
            id: $obj->id(),
            createdAt: $obj->createdAt,
        );
    }
}
