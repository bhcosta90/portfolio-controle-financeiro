<?php

namespace App\Jobs;

use App\Traits\ChargeTrait;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Str;

class GenerateChargeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ChargeTrait, ShouldBeUnique;

    protected string $dateStart;

    protected string $dateFinish;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $dateStart, string $dateFinish)
    {
        $this->dateStart = $dateStart;
        $this->dateFinish = $dateFinish;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $results = DB::table('charges')
            ->whereBetween('due_date', [$this->dateStart, $this->dateFinish])
            ->whereNotNull('type')
            ->where('future', true)
            ->get();

        $dateFinish = Carbon::createFromFormat('Y-m-d', $this->dateFinish);

        try {
            DB::beginTransaction();
            foreach ($results as $rs) {

                $dataTypesFuture = $this->calculate(
                    $rs->type,
                    new Carbon($rs->due_date),
                    $rs->type == 'every_last_day'
                        ? $dateFinish->firstOfMonth()
                        : $dateFinish->firstOfMonth()->addMonth(),
                    ['first_date' => false]
                );

                foreach ($dataTypesFuture as $rsDates) {
                    $dataInsert = (array) $rs;
                    DB::table('charges')->insert([
                        'id' => null,
                        'uuid' => Str::uuid(),
                        'future' => true
                    ] + $dataInsert);
                }

                DB::commit();
            }

            DB::table('charges')
                ->whereBetween('due_date', [$this->dateStart, $this->dateFinish])
                ->whereNotNull('type')
                ->where('future', true)
                ->update(['future' => false]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function uniqueId()
    {
        return md5($this->dateStart . $this->dateFinish);
    }
}
