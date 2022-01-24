<?php

namespace App\Http\Controllers\Web;

use App\Forms\Income\{ParcelForm, RecursiveForm, SimpleForm};
use App\Http\Controllers\Controller;
use App\Services\IncomeService;
use Costa\LaravelPackage\Traits\Web\WebBaseControllerTrait;
use Costa\LaravelTable\TableSimple;

class IncomeController extends Controller
{
    use WebBaseControllerTrait;

    protected function getDefaultView()
    {
        return 'income';
    }

    protected function getActionStore()
    {
        return route('income.store');
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
        /** @var TableSimple $table */
        $table = app(TableSimple::class);
        $table->setData($this->transformData($serviceData));
        return $table->run();
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
