<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CostResource;
use App\Models\Charge;
use App\Services\CostService;
use Costa\Package\Traits\Api\BaseControllerTrait;
use Illuminate\Http\Request;

class CostController extends Controller
{
    use BaseControllerTrait;

    protected function service(): string
    {
        return CostService::class;
    }

    protected function resource(): string
    {
        return CostResource::class;
    }

    protected function ruleUpdate(): array
    {
        return $this->ruleStore();
    }

    protected function ruleStore(): array
    {
        return [
            'value' => 'required|numeric|min:0|max:999999999',
            'customer_name' => 'required|min:3|max:150',
            'due_date' => 'required|date_format:d/m/Y',
            'type' => 'nullable|in' . implode(',', array_keys(Charge::$typeOptions)),
        ];
    }
}
