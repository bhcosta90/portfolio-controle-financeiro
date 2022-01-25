<?php

namespace App\Console\Commands;

use App\Jobs\GenerateChargeJob;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateChargeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'charge:generate {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dateStart = Carbon::createFromFormat('Y-m', $this->option('date'))->startOfMonth()->format('Y-m-d');
        $dateFinish = Carbon::createFromFormat('Y-m', $this->option('date'))->lastOfMonth()->format('Y-m-d');
        GenerateChargeJob::dispatch($dateStart, $dateFinish);
        return 0;
    }
}
