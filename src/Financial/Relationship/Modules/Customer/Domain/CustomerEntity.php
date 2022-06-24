<?php

namespace Core\Financial\Relationship\Modules\Customer\Domain;

use Core\Financial\Account\Contracts\AccountInterface;
use Core\Financial\Relationship\Shared\Enums\DocumentEnum;
use Core\Financial\Relationship\Shared\ValueObject\DocumentObject;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\Input\NameInputObject;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;

class CustomerEntity extends EntityAbstract implements AccountInterface
{
    private function __construct(
        protected NameInputObject $name,
        protected ?DocumentObject $document,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }

    public static function create(
        string $name,
        ?string $document_type,
        ?string $document_value,
        ?string $id = null,
        ?string $createdAt = null,
    ): self {
        return new self(
            name: new NameInputObject($name),
            document: $document_value && $document_type
                ? new DocumentObject(DocumentEnum::from($document_type), $document_value)
                : null,
            id: $id ? new UuidObject($id) : null,
            createdAt: $createdAt ? new DateTime($createdAt) : null,
        );
    }

    public function update(
        string $name,
        ?string $document_type,
        ?string $document_value,
    ): self {
        $this->name = new NameInputObject($name);
        $this->document = $document_value && $document_type
            ? new DocumentObject(DocumentEnum::from($document_type), $document_value)
            : null;

        return $this;
    }

    public function getEntityAccount(object $input): EntityAbstract
    {
        return self::create(
            name: $input->name,
            document_type: $input->document_type,
            document_value: $input->document_value,
            id: $input->id,
            createdAt: $input->created_at,
        );
    }
}
