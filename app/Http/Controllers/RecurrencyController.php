<?php

namespace App\Http\Controllers;

use App\Forms\RecurrencyForm;
use App\Services\RecurrencyService;
use Costa\LaravelPackage\Traits\Support\TableTrait;
use Costa\LaravelPackage\Traits\Web\WebCreateTrait;
use Costa\LaravelPackage\Traits\Web\WebEditTrait;
use Costa\LaravelPackage\Traits\Web\WebIndexTrait;

class RecurrencyController extends Controller
{
    use WebIndexTrait, TableTrait, WebEditTrait, WebCreateTrait;

    protected function view(): string
    {
        return 'recurrency';
    }

    protected function service(): string
    {
        return RecurrencyService::class;
    }

    protected function getTableColumns(): array
    {
        return [
            __('Nome') => fn ($obj) => __($obj->name),
            '_edit' => [
                'action' => fn ($obj) => btnLinkEditIcon(route('recurrency.edit', $obj->uuid)),
                'class' => 'min',
            ],
            '_destroy' => [
                'action' => fn ($obj) => btnLinkDelIcon(route('recurrency.destroy', $obj->uuid)),
                'class' => 'min',
            ],
        ];
    }

    protected function form(): string
    {
        return RecurrencyForm::class;
    }

    protected function routeUpdate($obj): string
    {
        return route('recurrency.update', $obj->uuid);
    }

    protected function routeStore(): string
    {
        return route('recurrency.store');
    }

    protected function routeRedirectPostPut(): string
    {
        return route('recurrency.index');
    }

    protected function getModelEdit($obj)
    {
        $obj->days = collect(explode('|', $obj->type))->first();
        return $obj;
    }
}
