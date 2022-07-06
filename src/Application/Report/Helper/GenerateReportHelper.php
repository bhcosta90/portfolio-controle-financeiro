<?php

namespace Core\Application\Report\Helper;

use Core\Application\Report\Domain\ReportDomain;
use Core\Application\Report\Type\Abstracts\ReportTypeAbstract;

class GenerateReportHelper
{
    public static function handle(string $title, ?string $type, ?string $subtitle = null): ReportTypeAbstract
    {
        if ($type === null) {
            $type = 'html';
        }
        $objDomain = ReportDomain::create($type, $title, $subtitle);
        $report = $objDomain->report;
        $report->title = $objDomain->title;
        $report->subtitle = $objDomain->resume;
        $report->addReport();
        return $report;
    }
}
