<?php

namespace App\Jobs;

use Costa\Modules\Payment\Contracts\PaymentEventManagerContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Costa\Modules\Payment\UseCases\PaymentUseCase;
use Costa\Modules\Payment\UseCases\DTO\Payment\Input;

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
    public function handle(PaymentUseCase $paymentUseCase) {
        tenancy()->initialize($this->tenantId);
        $data = array_map(fn($rs) => $rs->id, $this->payment->toArray());
        $paymentUseCase->handle(new Input($data));
        tenancy()->end();
    }
}
