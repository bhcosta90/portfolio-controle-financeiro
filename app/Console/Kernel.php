<?php

namespace App\Console;

use DateTime;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $date = (new DateTime())->format('Y-m-d');
        $schedule->command("tenant:run payment:schedule --option='date={$date}'")->dailyAt('10:00:00');
        $schedule->command("queue:work --stop-when-empty")->everyMinute();
        $schedule->call(fn() => Http::get('http://controlefinanceiro.bhcosta90.dev.br'))->everyTwoMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
