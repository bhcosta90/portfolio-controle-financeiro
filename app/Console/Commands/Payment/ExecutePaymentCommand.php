<?php

namespace App\Console\Commands\Payment;

use Core\Application\Payment\Services\DTO\ExecutePayment\Input;
use Core\Application\Payment\Services\ExecutePaymentService;
use DateTime;
use Illuminate\Console\Command;

class ExecutePaymentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:execute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute all payments';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ExecutePaymentService $service)
    {
        return $service->handle(new Input(date: new DateTime()))->id;
    }
}
