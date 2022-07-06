<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Core\Application\Charge\Modules;
use Core\Application\Charge\Shared\Enums\ChargeStatusEnum;
use Core\Application\Report\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function month(Request $request)
    {
        $receive = DB::table('charges')
            ->where('tenant_id', $request->user()->tenant_id)
            ->where('entity', Modules\Receive\Domain\ReceiveEntity::class)
            ->whereIn('status', [ChargeStatusEnum::PENDING, ChargeStatusEnum::PARTIAL])
            ->whereBetween('date', $this->getMonth($request->month))
            ->sum(DB::raw('value_charge', 'value_pay'));

        $payment = DB::table('charges')
            ->where('tenant_id', $request->user()->tenant_id)
            ->where('entity', Modules\Payment\Domain\PaymentEntity::class)
            ->whereIn('status', [ChargeStatusEnum::PENDING, ChargeStatusEnum::PARTIAL])
            ->whereBetween('date', $this->getMonth($request->month))
            ->sum(DB::raw('value_charge', 'value_pay'));

        return response()->json([
            'total' => ($receive - $payment) ?? 0,
            'total_real' => str()->numberBr(($receive - $payment) ?? 0),
        ]);
    }

    public function index(string $report, Request $request)
    {
        $letter = substr($report, -1);
        $report = substr($report, 0, -1);
        $reportClass = app('Core\\Application\\Report\\Reports\\R' . $report);

        $objService = new Services\GenerateService($reportClass, $letter);
        $ret = $objService->handle(new Services\DTO\Generate\Input(
            $request->render ?? "html",
            $request->all()
        ));
        $title = $ret->report['title'] ?: "";
        $render = $ret->render();

        return view('admin.report.index', compact('render', 'title'));
    }

    private function getMonth(?string $month)
    {
        $date = new Carbon($month);
        return [$date->firstOfMonth()->format('Y-m-d'), $date->lastOfMonth()->format('Y-m-d')];
    }
}
