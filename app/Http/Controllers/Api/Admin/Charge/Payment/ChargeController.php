<?php

namespace App\Http\Controllers\Api\Admin\Charge\Payment;

use App\Http\Controllers\Controller;
use App\Support\TenantSupport;
use Carbon\Carbon;
use Core\Application\Charge\Shared\Enums\ChargeStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity as Entity;

class ChargeController extends Controller
{
    public function quantityToday(Request $request, TenantSupport $tenantSupport)
    {
        $ret = DB::table('charges')
            ->where('tenant_id', $tenantSupport->validate($request))
            ->where('entity', Entity::class)
            ->whereIn('status', [ChargeStatusEnum::PENDING])
            ->where('date', (new Carbon())->format('Y-m-d'))
            ->count();

        return response()->json([
            'quantity' => $ret,
        ]);
    }

    public function dueDate(Request $request, TenantSupport $tenantSupport)
    {
        $ret = DB::table('charges')
            ->where('tenant_id', $tenantSupport->validate($request))
            ->where('entity', Entity::class)
            ->whereIn('status', [ChargeStatusEnum::PENDING])
            ->where('date', '<', $this->getMonth($request->month)[0])
            ->count();

        return response()->json([
            'quantity' => $ret ?? 0,
        ]);
    }

    public function valueMonth(Request $request, TenantSupport $tenantSupport){
        $ret = DB::table('charges')
            ->where('tenant_id', $tenantSupport->validate($request))
            ->where('entity', Entity::class)
            ->whereIn('status', [ChargeStatusEnum::PENDING])
            ->whereBetween('date', $this->getMonth($request->month))
            ->sum(DB::raw('value_charge', 'value_pay'));

        return response()->json([
            'total' => $ret ?? 0,
            'total_real' => str()->numberBr($ret ?? 0),
        ]);
    }

    private function getMonth(?string $month)
    {
        $date = new Carbon($month);
        return [$date->firstOfMonth()->format('Y-m-d'), $date->lastOfMonth()->format('Y-m-d')];
    }
}
