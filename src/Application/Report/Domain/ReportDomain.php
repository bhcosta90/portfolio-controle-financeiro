<?php

namespace Core\Application\Report\Domain;

use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;

class ReportDomain extends EntityAbstract
{
    private function __construct(
        protected string      $title,
        protected ?string     $resume,
        protected ?UuidObject $id = null,
        protected ?DateTime   $createdAt = null,
    )
    {
        parent::__construct();
    }

    public static function create(
        string  $title,
        ?string $resume,
    )
    {
        return new self($title, $resume);
    }
}
