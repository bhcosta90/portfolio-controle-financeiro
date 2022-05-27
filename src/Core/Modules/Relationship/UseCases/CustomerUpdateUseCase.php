<?php

namespace Costa\Modules\Relationship\UseCases;

use Costa\Modules\Relationship\Repositories\CustomerRepositoryInterface;
use Costa\Shared\ValueObject\DocumentObject;
use Costa\Shared\ValueObject\Enums\DocumentEnum;
use Costa\Shared\ValueObject\Input\InputNameObject;

class CustomerUpdateUseCase
{
    public function __construct(
        protected CustomerRepositoryInterface $relationship
    ) {
        //
    }

    public function handle(DTO\Update\Input $input): DTO\Create\Output
    {
        /** @var \Costa\Modules\Relationship\CustomerEntity */
        $obj = $this->relationship->find($input->id);

        $obj->update(
            name: new InputNameObject($input->name),
            document: $input->documentValue
                ? new DocumentObject(DocumentEnum::from($input->documentType), $input->documentValue)
                : null
        );

        $this->relationship->update($obj);

        return new DTO\Create\Output(
            id: $obj->id,
            name: $obj->name,
            document: $obj->document,
        );
    }
}
