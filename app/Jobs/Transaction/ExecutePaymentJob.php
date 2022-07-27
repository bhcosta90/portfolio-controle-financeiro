<?php

namespace App\Jobs\Transaction;

use Core\Application\Transaction\Events\ExecutePaymentEvent;
use Core\Application\Transaction\UseCases\ExecuteUseCase;
use Core\Application\Transaction\UseCases\DTO\Execute\Input;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExecutePaymentJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private ExecutePaymentEvent $executePaymentEvent)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ExecuteUseCase $executeUseCase)
    {
        $data = $this->executePaymentEvent->payload();
        $executeUseCase->handle(new Input($data['tenant'], $data['id']));
    }

    public function uniqueId()
    {
        $data = $this->executePaymentEvent->payload();
        return sha1($data['tenant'] . $data['id']);
    }
}
