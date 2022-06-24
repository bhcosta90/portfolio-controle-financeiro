<?php

namespace Core\Financial\Relationship\Modules\Company\UseCases;

use Core\Financial\Relationship\Modules\Company\Domain\CompanyEntity as Entity;
use Core\Financial\Relationship\Modules\Company\Repository\CompanyRepositoryInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Throwable;

class UpdateUseCase
{
    public function __construct(
        private CompanyRepositoryInterface $repo,
    ) {
        //
    }

    public function handle(DTO\Update\UpdateInput $input): DTO\Update\UpdateOutput
    {
        /** @var Entity */
        $obj = $this->repo->find($input->id);

        $obj->update(
            name: $input->name,
            document_type: $input->document_type,
            document_value: $input->document_value,
        );
        
        $this->repo->update($obj);

        return new DTO\Update\UpdateOutput(
            id: $obj->id(),
            name: $obj->name->value,
            document_type: $obj->document?->type->value,
            document_value: $obj->document?->document,
        );
    }
}
