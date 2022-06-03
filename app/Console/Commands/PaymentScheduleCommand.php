<?php

namespace App\Console\Commands;

use App\Jobs\PaymentScheduleJob;
use Costa\Modules\Payment\Entity\PaymentEntity;
use Costa\Modules\Payment\Shared\Enums\PaymentType;
use Costa\Shared\ValueObject\UuidObject;
use DateTime;
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
    protected $description = 'Payment Schedule';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = (new DateTime(str_pad($this->option('date'), 10, "01-", STR_PAD_LEFT)))->format('Y-m-d');
        $tenantId = tenant()->id;

        $i = 0;

        do {
            tenancy()->initialize($tenantId);
            $results = DB::table('payments')->where('completed', false)
                ->where('date_schedule', '<=', $date)
                ->select('account_from_id')
                ->limit($total = 1000)
                ->whereNotNull('account_from_id')
                ->groupBy('account_from_id')
                ->offset($i)
                ->get();

            foreach ($results as $rs) {
                tenancy()->initialize($tenantId);
                do {
                    $results = DB::table('payments')->where('completed', false)
                        ->where('date_schedule', '<=', $date)
                        ->select()
                        ->orderBy('created_at')
                        ->limit($total)
                        ->where('account_from_id', $rs->account_from_id)
                        ->offset($i)
                        ->get();

                    dispatch(new PaymentScheduleJob((string) tenant()->id, $results));
                } while ($results->count() == $total);
            }
        } while ($results->count() == $total);

        $i = 0;
        do {
            tenancy()->initialize($tenantId);
            $results = DB::table('payments')->where('completed', false)
                ->where('date_schedule', '<=', $date)
                ->select('account_to_id')
                ->limit($total = 1000)
                ->whereNotNull('account_to_id')
                ->groupBy('account_to_id')
                ->offset($i)
                ->get();

            foreach ($results as $rs) {
                tenancy()->initialize($tenantId);
                do {
                    $results = DB::table('payments')->where('completed', false)
                        ->where('date_schedule', '<=', $date)
                        ->select()
                        ->orderBy('created_at')
                        ->limit($total)
                        ->where('account_to_id', $rs->account_to_id)
                        ->offset($i)
                        ->get();

                    dispatch(new PaymentScheduleJob((string) tenant()->id, $results));
                } while ($results->count() == $total);
            }
        } while ($results->count() == $total);

        return 0;
    }
}
