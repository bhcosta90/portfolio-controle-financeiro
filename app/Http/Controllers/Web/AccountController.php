<?php

namespace App\Http\Controllers\Web;

use App\Forms\AccountForm;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use Costa\LaravelPackage\Traits\Web\WebBaseControllerTrait;
use Costa\LaravelTable\TableSimple;

class AccountController extends Controller
{
    use WebBaseControllerTrait;

    protected function getDefaultView()
    {
        return 'account';
    }

    protected function getActionStore()
    {
        return route('account.store');
    }

    protected function getData($serviceData)
    {
        $table = app(TableSimple::class);
        $table->setData($this->transformData($serviceData));
        $table->setColumns([
            'name' => __('Name'),
            'bank_code' => __('Code bank'),
            'bank_account' => __('Account bank'),
            'bank_digit' => __('Digit account')
        ]);

        $table->setAddColumns([
             'edit' => [
                'action' => function ($model) {
                    return btnLinkEditIcon(route('account.edit', $model->uuid));
                },
                'class' => 'min',
            ],
            'delete' => [
                'action' => function ($model) {
                    return btnLinkDelIcon(route('account.destroy', $model->uuid));
                },
                'class' => 'min',
            ]
        ]);

        return $table->run();
    }

    protected function getActionUpdate()
    {
        return route('account.update', $this->obj->uuid);
    }

    protected function getActionIndex()
    {
        return route('account.index');
    }

    protected function service()
    {
        return AccountService::class;
    }

    protected function getForm()
    {
        return AccountForm::class;
    }
}
