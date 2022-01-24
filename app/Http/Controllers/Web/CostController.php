<?php

namespace App\Http\Controllers\Web;

use App\Forms\Cost\{ParcelForm, RecursiveForm, SimpleForm};
use App\Http\Controllers\Controller;
use App\Services\CostService;
use Costa\LaravelPackage\Traits\Web\WebBaseControllerTrait;
use Costa\LaravelTable\TableSimple;
use Illuminate\Support\Str;

class CostController extends Controller
{
    use WebBaseControllerTrait;

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

    protected function service()
    {
        return CostService::class;
    }

    protected function getData($serviceData)
    {
        /** @var TableSimple $table */
        $table = app(TableSimple::class);
        $table->setData($this->transformData($serviceData));
        $table->setColumns(false);
        $table->setAddColumns([
            __('Customer name') => fn ($obj) => $obj->charge->customer_name,
            __('Value') => fn ($obj) => Str::numberEnToBr($obj->charge->value),
        ]);
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
