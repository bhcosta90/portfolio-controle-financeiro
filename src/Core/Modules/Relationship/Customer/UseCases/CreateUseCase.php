<?php

namespace Costa\Modules\Relationship\Customer\UseCases;

use Costa\Modules\Relationship\Customer\Entity\CustomerEntity;
use Costa\Modules\Relationship\Customer\Repository\CustomerRepositoryInterface;
use Costa\Shared\ValueObject\DocumentObject;
use Costa\Shared\ValueObject\Enums\DocumentEnum;
use Costa\Shared\ValueObject\Input\InputNameObject;

class CreateUseCase
{
    public function __construct(
        protected CustomerRepositoryInterface $relationship
    ) {
        //
    }

    public function handle(DTO\Create\Input $input): DTO\Create\Output
    {
        $objEntity = new CustomerEntity(
            name: new InputNameObject($input->name),
            document: $input->documentValue
                ? new DocumentObject(DocumentEnum::from($input->documentType), $input->documentValue)
                : null
        );

        $this->relationship->insert($objEntity);

        return new DTO\Create\Output(
            id: $objEntity->id,
            name: $objEntity->name->value,
            document_type: $objEntity->document?->type->value,
            document_value: $objEntity->document?->document,
        );
    }
}
