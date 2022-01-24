<?php

namespace App\Http\Controllers\Web;

use App\Forms\CostForm;
use App\Http\Controllers\Controller;
use App\Services\CostService;
use Costa\LaravelPackage\Traits\Web\WebBaseControllerTrait;
use Costa\LaravelTable\TableSimple;

class CostController extends Controller
{
    use WebBaseControllerTrait;

    protected function getDefaultView()
    {
        return 'cost';
    }

    protected function getActionStore()
    {
        return route('cost.store');
    }

    protected function getActionIndex()
    {
        return route('cost.index');
    }

    protected function service()
    {
        return CostService::class;
    }

    protected function getData($serviceData)
    {
        /** @var TableSimple $table */
        $table = app(TableSimple::class);
        $table->setData($this->transformData($serviceData));
        return $table->run();
    }

    protected function getForm()
    {
        return CostForm::class;
    }
}
