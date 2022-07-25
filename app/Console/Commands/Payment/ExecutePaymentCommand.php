<?php

namespace App\Console\Commands\Payment;

use Core\Application\Transaction\UseCases\ExecuteSchedulePaymentUseCase;
use Core\Application\Transaction\UseCases\DTO\ExecuteSchedulePayment\Input;
use DateTime;
use Illuminate\Console\Command;

class ExecutePaymentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:execute {--date=}';

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
    public function handle(ExecuteSchedulePaymentUseCase $service)
    {
        return $service->handle(new Input(date: new DateTime($this->option('date'))));
    }
}
