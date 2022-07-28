<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Core\Report\Services;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(string $report, Request $request)
    {
        $letter = substr($report, -1);
        $report = substr($report, 0, -1);
        $reportClass = app('Core\\Report\\Reports\\R' . $report);

        $objService = new Services\GenerateService($reportClass, $letter);
        $ret = $objService->handle(new Services\DTO\Generate\Input(
            $request->render ?? "html",
            $request->all()
        ));
        $title = $ret->report['title'] ?: "";
        $render = $ret->render();

        return view('admin.report.index', compact('render', 'title'));
    }
}
