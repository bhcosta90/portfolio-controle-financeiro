<?php

namespace App\Http\Controllers\Charge;

use App\Forms\Charge\IncomeForm;
use App\Http\Controllers\Controller;
use App\Services\IncomeService;
use Carbon\Carbon;
use Costa\LaravelPackage\Traits\Web\WebCreateTrait;
use Costa\LaravelPackage\Traits\Web\WebIndexTrait;

class IncomeController extends Controller
{
    use WebIndexTrait, WebCreateTrait;

    protected function view(): string
    {
        return 'income';
    }

    protected function service(): string
    {
        return IncomeService::class;
    }

    protected function routeStore(): string
    {
        return route('income.store');
    }

    protected function routeRedirectPostPut(): string
    {
        return route('income.index');
    }

    protected function form(): string
    {
        return IncomeForm::class;
    }

    protected function getActionCreate(): array
    {
        $token = request()->user()->getTokenCustomer()->plainTextToken;

        return [
            'token' => $token,
        ];
    }

    protected function getModelCreate()
    {
        return [
            'due_date' => (new Carbon())->format('Y-m-d'),
            'parcel' => 0,
        ];
    }
}
