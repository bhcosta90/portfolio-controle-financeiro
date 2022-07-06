<?php

namespace Core\Application\Report\Domain;

use Core\Application\Report\Type\Abstracts\ReportTypeAbstract;
use Core\Application\Report\Type as ReportType;
use Core\Shared\Abstracts\EntityAbstract;
use DomainException;

class ReportDomain extends EntityAbstract
{
    private function __construct(
        protected ReportTypeAbstract $report,
        protected string $title,
        protected ?string $resume,
    ) {
        //
    }

    public static function create(
        string $report,
        string $title,
        ?string $resume,
    ) {
        $report = match ($report) {
            'html' => new ReportType\Html(),
            default => throw new DomainException('Type do not implemented'),
        };

        /** @var ReportTypeAbstract $report */
        return new self($report, $title, $resume);
    }
}
