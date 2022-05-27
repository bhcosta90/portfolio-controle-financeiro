<?php

namespace Costa\Modules\Relationship\UseCases;

use Costa\Modules\Relationship\Repositories\SupplierRepositoryInterface;
use Costa\Modules\Relationship\SupplierEntity;
use Costa\Shared\ValueObject\DocumentObject;
use Costa\Shared\ValueObject\Enums\DocumentEnum;
use Costa\Shared\ValueObject\Input\InputNameObject;

class SupplierCreateUseCase
{
    public function __construct(
        protected SupplierRepositoryInterface $relationship
    ) {
        //
    }

    public function handle(DTO\Create\Input $input): DTO\Create\Output
    {
        $objEntity = new SupplierEntity(
            name: new InputNameObject($input->name),
            document: $input->documentValue
                ? new DocumentObject(DocumentEnum::from($input->documentType), $input->documentValue)
                : null
        );

        $this->relationship->insert($objEntity);

        return new DTO\Create\Output(
            id: $objEntity->id,
            name: $objEntity->name,
            document: $objEntity->document,
        );
    }

}
