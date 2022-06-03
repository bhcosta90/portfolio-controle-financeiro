<?php

namespace Costa\Modules\Relationship\Supplier\UseCases;

use Costa\Modules\Account\Entity\AccountEntity;
use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Relationship\Supplier\Entity\SupplierEntity;
use Costa\Modules\Relationship\Supplier\Repository\SupplierRepositoryInterface;
use Costa\Shared\Contracts\TransactionContract;
use Costa\Shared\ValueObject\DocumentObject;
use Costa\Shared\ValueObject\Enums\DocumentEnum;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\ModelObject;
use Throwable;

class CreateUseCase
{
    public function __construct(
        protected SupplierRepositoryInterface $relationship,
        protected AccountRepositoryInterface $account,
        protected TransactionContract $transaction,
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

        $objAccount = new AccountEntity(new ModelObject($objEntity->id(), $objEntity), 0);

        try {
            $this->relationship->insert($objEntity);
            $this->account->insert($objAccount);
            $this->transaction->commit();

            return new DTO\Create\Output(
                id: $objEntity->id,
                name: $objEntity->name->value,
                document_type: $objEntity->document?->type->value,
                document_value: $objEntity->document?->document,
            );
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
