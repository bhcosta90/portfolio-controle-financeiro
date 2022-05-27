<?php

namespace App\Console\Commands;

use App\Jobs\ChargePaymentJob;
use App\Models\Tenant;
use Costa\Modules\Charge\UseCases\Payment\PaymentScheduleUseCase;
use Costa\Modules\Payment\Shareds\Enums\Type;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PaymentScheduleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:schedule {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Payment schedule';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = $this->option('date');

        $result = DB::table('payments')
            ->where('completed', 0)->where('date_schedule', $date)
            ->get();

        foreach ($result as $rs) {
            dispatch(new ChargePaymentJob($rs->uuid, $rs->type, $rs->value_payment, $rs->account_id, $rs->tenant_id));
        }

        return 0;
    }
}
