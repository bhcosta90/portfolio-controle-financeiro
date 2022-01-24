<?php

namespace Database\Seeders\Traits;

use App\Services\BaseCostIncomeService;
use Illuminate\Database\Eloquent\Model;

trait ChargeTrait
{
    abstract protected function data();

    abstract protected function service();

    public function run()
    {
        Model::reguard();
        /** @var BaseCostIncomeService */
        $service = app($this->service());

        foreach($this->data() as $rs){
            $service->webStore($rs + ['user_id' => 1]);
        }

        Model::unguard();
    }
}
