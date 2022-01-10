<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\IncomeResource;
use App\Models\Charge;
use App\Services\IncomeService;
use Costa\LaravelPackage\Traits\Api\BaseControllerTrait;

class IncomeController extends Controller
{
    use BaseControllerTrait;

    protected function service(): string
    {
        return IncomeService::class;
    }

    protected function resource(): string
    {
        return IncomeResource::class;
    }

    protected function ruleUpdate(): array
    {
        return [
            'value' => 'required|numeric|min:0|max:999999999',
            'customer_name' => 'required|min:3|max:150',
            'due_date' => 'required|date_format:d/m/Y',
            'type' => 'nullable|in:' . implode(',', array_keys(Charge::$typeOptions)),
        ];
    }

    protected function ruleStore(): array
    {
        return $this->ruleUpdate() + [
            'parcel_total' => 'nullable|numeric|max:360|min:1',
        ];
    }
}
