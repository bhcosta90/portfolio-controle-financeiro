<?php

namespace App\Http\Controllers\Web;

use App\Forms\ChargeForm;
use App\Http\Controllers\Controller;
use App\Models\Cost;
use App\Models\Income;
use App\Services\ChargeService;
use Costa\LaravelPackage\Traits\Web\WebBaseControllerTrait;
use Exception;

class ChargeController extends Controller
{
    use WebBaseControllerTrait;

    protected function getDefaultView()
    {
        return 'charge';
    }

    protected function getActionStore()
    {
        throw new Exception('Store do not implemented');
    }

    protected function getActionUpdate()
    {
        return route('charge.update', $this->obj->uuid);
    }

    protected function getActionIndex()
    {
        switch ($this->obj->chargeable_type) {
            case Cost::class;
                return route('cost.index');
                break;
            case Income::class;
                return route('income.index');
                break;
        }
    }

    protected function service()
    {
        return ChargeService::class;
    }

    protected function getForm()
    {
        return ChargeForm::class;
    }
}
