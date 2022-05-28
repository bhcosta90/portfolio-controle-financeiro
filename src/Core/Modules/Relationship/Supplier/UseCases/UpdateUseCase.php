<?php

namespace Costa\Modules\Relationship\Supplier\UseCases;

use Costa\Modules\Relationship\Supplier\Entity\SupplierEntity;
use Costa\Modules\Relationship\Supplier\Repository\SupplierRepositoryInterface;
use Costa\Shared\ValueObject\DocumentObject;
use Costa\Shared\ValueObject\Enums\DocumentEnum;
use Costa\Shared\ValueObject\Input\InputNameObject;

class UpdateUseCase
{
    public function __construct(
        protected SupplierRepositoryInterface $relationship
    ) {
        //
    }

    public function handle(DTO\Update\Input $input): DTO\Update\Output
    {
        /** @var SupplierEntity */
        $objEntity = $this->relationship->find($input->id);
        $objEntity->update(
            name: new InputNameObject($input->name),
            document: $input->documentValue
                ? new DocumentObject(DocumentEnum::from($input->documentType), $input->documentValue)
                : null
        );

        $this->relationship->update($objEntity);

        return new DTO\Update\Output(
            id: $objEntity->id,
            name: $objEntity->name->value,
            document_type: $objEntity->document?->type->value,
            document_value: $objEntity->document?->document,
        );
    }
}
