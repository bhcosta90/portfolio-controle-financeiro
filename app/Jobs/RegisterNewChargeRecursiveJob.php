<?php

namespace App\Jobs;

use App\Models\Charge;
use App\Models\Cost;
use App\Models\Income;
use App\Services\ChargeService;
use Carbon\Carbon;
use Costa\LaravelPackage\Utils\Recursive;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RegisterNewChargeRecursiveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private Charge $obj)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $obj = $this->obj;

        switch ($obj->chargeable_type) {
            case Income::class:
            case Cost::class:

                /** @var Recursive $objRecursive */
                $dates = app(Recursive::class)->calculate(
                    $obj->recurrency->type,
                    (new Carbon($obj->due_date)),
                    (new Carbon($obj->due_date))->addMonth()->lastOfMonth(),
                    ['first_date' => false]
                );

                $model = app($obj->chargeable_type);
                $objModel = $model->create([]);
                $this->getChargeService()->create($model, [
                    'basecharge_type' => $obj->basecharge_type,
                    'basecharge_id' => $obj->basecharge_id,
                    'due_date' => $dates[0]['date_week'],
                    'date_start' => $dates[0]['date_week'],
                    'date_end' => $dates[0]['date_week'],
                    'recurrency_id' => $obj->recurrency_id,
                    'user_id' => $obj->user_id,
                    'resume' => $obj->resume,
                    'value' => $obj->value,
                    'value_recurrency' => $obj->value,
                    'customer_name' => $obj->customer_name,
                    'chargeable_id' => $objModel->id,
                ]);
                break;
        }
    }

    /**
     * @return ChargeService
     */
    private function getChargeService()
    {
        return app(ChargeService::class);
    }
}
