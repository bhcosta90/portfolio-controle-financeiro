<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Support\TenantSupport;
use Carbon\Carbon;
use Core\Application\Charge\Modules;
use Core\Application\Charge\Shared\Enums\ChargeStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function month(Request $request, TenantSupport $tenantSupport)
    {
        $tenant = $tenantSupport->validate($request);

        $receive = DB::table('charges')
            ->where('tenant_id', $tenant)
            ->where('entity', Modules\Receive\Domain\ReceiveEntity::class)
            ->whereIn('status', [ChargeStatusEnum::PENDING])
            ->whereBetween('date', $this->getMonth($request->month))
            ->sum(DB::raw('value_charge', 'value_pay'));

        $payment = DB::table('charges')
            ->where('tenant_id', $tenant)
            ->where('entity', Modules\Payment\Domain\PaymentEntity::class)
            ->whereIn('status', [ChargeStatusEnum::PENDING])
            ->whereBetween('date', $this->getMonth($request->month))
            ->sum(DB::raw('value_charge', 'value_pay'));

        return response()->json([
            'total' => ($receive - $payment) ?? 0,
            'total_real' => str()->numberBr(($receive - $payment) ?? 0),
        ]);
    }

    private function getMonth(?string $month)
    {
        $date = new Carbon($month);
        return [$date->firstOfMonth()->format('Y-m-d'), $date->lastOfMonth()->format('Y-m-d')];
    }
}
