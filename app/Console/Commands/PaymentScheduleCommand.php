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
        $date = (new DateTime(str_pad($this->option('date'),10, "01-", STR_PAD_LEFT)))->format('Y-m-d');

        $results = DB::table('payments')->where('completed', false)
            ->where('date_schedule', $date)
            ->select()
            ->orderBy('created_at')
            ->get();

        foreach ($results as $rs) {
            $payment = new PaymentEntity(
                relationship: $rs->relationship_id,
                charge: $rs->charge_id,
                date: new DateTime($rs->date_schedule),
                value: $rs->value_payment,
                type: PaymentType::from($rs->type),
                accountFrom: $rs->account_from_id,
                accountTo: $rs->account_to_id,
                id: new UuidObject($rs->id),
                createdAt: new DateTime($rs->created_at)
            );
            $payment->completed();
            dispatch(new PaymentScheduleJob((string) tenant()->id, $payment));
            sleep(1);
        }
        return 0;
    }
}
