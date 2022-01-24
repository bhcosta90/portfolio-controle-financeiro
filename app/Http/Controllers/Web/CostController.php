<?php

namespace App\Http\Controllers\Web;

use App\Forms\Cost\{ParcelForm, RecursiveForm, SimpleForm};
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
        switch(request()->segment(3)){
            case 'recursive':
                return RecursiveForm::class;
            case 'parcel':
                return ParcelForm::class;
            case 'normal':
                return SimpleForm::class;
        }
    }
}
