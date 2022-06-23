<?php

namespace Core\Financial\Relationship\Domain;

use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\Input\NameInputObject;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;

abstract class RelationshipEntityAbstract extends EntityAbstract
{
    private function __construct(
        protected NameInputObject $name,
        protected string $document,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }

    public static function create(
        string $name,
        string $document,
    ) {
        return new static(
            name: new NameInputObject($name),
            document: $document
        );
    }

    public function update(
        string $name,
        string $document,
    ) {
        $this->name = new NameInputObject($name);
        $this->document = $document;
    }
}
