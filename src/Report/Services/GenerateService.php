<?php

namespace Core\Report\Services;

use Core\Report\Type as ReportType;
use DomainException;

class GenerateService
{
    public function __construct(
        private object $report,
        private string $letter,
    )
    {
        //
    }

    public function handle(DTO\Generate\Input $input)
    {
        $render = 'render_' . $this->letter;

        $report = match ($input->render) {
            'html' => new ReportType\Html(),
            default => throw new DomainException('Type do not implemented'),
        };

        $this->report->$render($report);

        return $report;
    }
}
