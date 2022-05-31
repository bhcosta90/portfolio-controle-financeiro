<?php

namespace App\Jobs\Payment;

use Costa\Modules\Payment\Contracts\PaymentEventManagerContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Costa\Modules\Payment\Entity\PaymentEntity;
use Costa\Modules\Payment\Repository\PaymentRepositoryInterface;
use Costa\Shared\Contracts\TransactionContract;
use Throwable;

class PaymentScheduleJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private string $tenantId, private PaymentEntity $payment)
    {
        // tenancy()->initialize($tenant);
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
            $paymentEventManager->dispatch($this->payment);
            $repo->update($this->payment);
            $transaction->commit();
            tenancy()->end();
        } catch (Throwable $e) {
            $transaction->rollback();
            throw $e;
        }
    }

    public function uniqueId()
    {
        return $this->tenantId . $this->payment->id();
    }
}
