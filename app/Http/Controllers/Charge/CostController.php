<?php

namespace App\Http\Controllers\Charge;

use App\Forms\Charge\CostForm;
use App\Http\Controllers\Controller;
use App\Services\CostService;
use Carbon\Carbon;
use Costa\LaravelPackage\Traits\Web\WebCreateTrait;
use Costa\LaravelPackage\Traits\Web\WebIndexTrait;

class CostController extends Controller
{
    use WebIndexTrait, WebCreateTrait;

    protected function view(): string
    {
        return 'cost';
    }

    protected function service(): string
    {
        return CostService::class;
    }

    protected function routeStore(): string
    {
        return route('cost.store');
    }

    protected function routeRedirectPostPut(): string
    {
        return route('cost.index');
    }

    protected function form(): string
    {
        return CostForm::class;
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
