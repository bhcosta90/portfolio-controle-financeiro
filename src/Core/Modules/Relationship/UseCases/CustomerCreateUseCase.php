<?php

namespace Costa\Modules\Relationship\UseCases;

use App\Repositories\Eloquent\CustomerRepository;
use Costa\Modules\Relationship\CustomerEntity;
use Costa\Modules\Relationship\Repositories\CustomerRepositoryInterface;
use Costa\Shared\ValueObject\DocumentObject;
use Costa\Shared\ValueObject\Enums\DocumentEnum;
use Costa\Shared\ValueObject\Input\InputNameObject;

class CustomerCreateUseCase
{
    /** @param CustomerRepository $relationship */
    public function __construct(
        private CustomerRepositoryInterface $relationship
    ) {
        //
    }

    public function handle(DTO\Create\Input $input): DTO\Create\Output
    {
        $obj = $this->getObject();

        $objEntity = new $obj(
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

    protected function getObject()
    {
        return CustomerEntity::class;
    }
}
