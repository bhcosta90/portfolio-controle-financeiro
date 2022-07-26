<?php

namespace App\Http\Controllers\Api\Admin\Account;

use App\Http\Controllers\Controller;
use App\Support\TenantSupport;
use Carbon\Carbon;
use Core\Application\BankAccount\Modules\Bank\Domain\BankEntity;
use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity;
use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity;
use Core\Application\Charge\Shared\Enums\ChargeStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    public function financial(Request $request, TenantSupport $tenantSupport)
    {
        $tenant = $tenantSupport->validate($request);

        $dateStart = (new Carbon())->firstOfMonth()->format('Y-m-d');
        $dateFinish = (new Carbon($request->month))->lastOfMonth()->format('Y-m-d');

        $valueBank = DB::table('accounts')
            ->where('entity_type', BankEntity::class)
            ->where('tenant_id', $tenant)
            ->sum('value');

        $valueReceive = DB::table('charges')
            ->where('tenant_id', $tenant)
            ->where('status', [ChargeStatusEnum::PENDING])
            ->where('entity', ReceiveEntity::class)
            ->whereBetween('charges.date', [$dateStart, $dateFinish])
            ->sum(DB::raw('value_charge - value_pay'));

        $valuePayment = DB::table('charges')
            ->where('tenant_id', $tenant)
            ->where('status', [ChargeStatusEnum::PENDING])
            ->where('entity', PaymentEntity::class)
            ->whereBetween('charges.date', [$dateStart, $dateFinish])
            ->sum(DB::raw('value_charge - value_pay'));

        $value = $valueBank + $valueReceive - $valuePayment;
        
        return response()->json([
            'total' => $value ?? 0,
            'total_real' => str()->numberBr($value ?? 0),
        ]);
    }
}
