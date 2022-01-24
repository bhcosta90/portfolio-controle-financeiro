<?php

namespace App\Http\Controllers\Web;

use App\Forms\Cost\{ParcelForm, RecursiveForm, SimpleForm};
use App\Http\Controllers\Controller;
use App\Services\CostService;
use Costa\LaravelPackage\Traits\Web\WebBaseControllerTrait;

class CostController extends Controller
{
    use WebBaseControllerTrait, Traits\CostIncomeTrait;

    protected function getPaginateSize()
    {
        return 30;
    }

    protected function getDefaultView()
    {
        return 'cost';
    }

    protected function getActionStore()
    {
        switch (request()->segment(3)) {
            case 'recursive':
                return route('cost.store.recursive');
            case 'parcel':
                return route('cost.store.parcel');
            case 'normal':
                return route('cost.store.normal');
        }
    }

    protected function getActionIndex()
    {
        return route('cost.index');
    }

    protected function getData($serviceData)
    {
        return $this->costIncomeTraitGetData($serviceData);
    }

    protected function service()
    {
        return CostService::class;
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
