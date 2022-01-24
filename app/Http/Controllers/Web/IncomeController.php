<?php

namespace App\Http\Controllers\Web;

use App\Forms\Income\{ParcelForm, RecursiveForm, SimpleForm};
use App\Http\Controllers\Controller;
use App\Services\IncomeService;
use Costa\LaravelPackage\Traits\Web\WebBaseControllerTrait;

class IncomeController extends Controller
{
    use WebBaseControllerTrait, Traits\CostIncomeTrait;

    protected function getPaginateSize()
    {
        return 30;
    }

    protected function getDefaultView()
    {
        return 'income';
    }

    protected function getActionStore()
    {
        switch (request()->segment(3)) {
            case 'recursive':
                return route('income.store.recursive');
            case 'parcel':
                return route('income.store.parcel');
            case 'normal':
                return route('income.store.normal');
        }
    }

    protected function getActionIndex()
    {
        return route('income.index');
    }

    protected function service()
    {
        return IncomeService::class;
    }

    protected function getData($serviceData)
    {
        return $this->costIncomeTraitGetData($serviceData);
    }

    protected function getForm()
    {
        switch (request()->segment(3)) {
            case 'recursive':
                return RecursiveForm::class;
            case 'parcel':
                return ParcelForm::class;
            case 'normal':
                return SimpleForm::class;
        }
    }
}
