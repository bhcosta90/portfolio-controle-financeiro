<?php

namespace Core\Financial\Relationship\Modules\Company\Domain;

use Core\Financial\Relationship\Shared\Enums\DocumentEnum;
use Core\Financial\Relationship\Shared\ValueObject\DocumentObject;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\Input\NameInputObject;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;

class CompanyEntity extends EntityAbstract
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
        ?int $document_type,
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
        ?int $document_type,
        ?string $document_value,
    ): self {
        $this->name = new NameInputObject($name);
        $this->document = $document_value && $document_type
            ? new DocumentObject(DocumentEnum::from($document_type), $document_value)
            : null;

        return $this;
    }
}
