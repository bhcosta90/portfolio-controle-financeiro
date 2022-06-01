<?php

namespace App\Jobs;

use Costa\Modules\Payment\Contracts\PaymentEventManagerContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Costa\Modules\Payment\Entity\PaymentEntity;
use Costa\Modules\Payment\Events\PaymentEvent;
use Costa\Modules\Payment\Repository\PaymentRepositoryInterface;
use Costa\Modules\Payment\Shared\Enums\PaymentType;
use Costa\Shared\Contracts\TransactionContract;
use Costa\Shared\ValueObject\UuidObject;
use DateTime;
use Throwable;

class PaymentScheduleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $tenantId;

    private object $payment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $tenantId, object $payment)
    {
        $this->tenantId = $tenantId;
        $this->payment = $payment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        TransactionContract $transaction,
        PaymentRepositoryInterface $repo,
        PaymentEventManagerContract $paymentEventManager
    ) {
        try {
            tenancy()->initialize($this->tenantId);
            foreach ($this->payment as $rs) {
                $objPayment = new PaymentEntity(
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
                $objPayment->completed();
                $paymentEventManager->dispatch(new PaymentEvent($objPayment));
                $repo->update($objPayment);
                $transaction->commit();
            }
            tenancy()->end();
        } catch (Throwable $e) {
            $transaction->rollback();
            throw $e;
        }
    }
}
