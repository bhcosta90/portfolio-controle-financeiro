<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FormPaymentResource;
use App\Models\FormPayment;
use App\Services\FormPaymentService;
use Costa\LaravelPackage\Traits\Api\ApiBaseControllerTrait;

class FormPaymentController extends Controller
{
    use ApiBaseControllerTrait;

    protected function service(): string
    {
        return FormPaymentService::class;
    }

    protected function ruleStore(): array
    {
        return $this->ruleUpdate();
    }

    protected function ruleUpdate(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:70'],
            'type' => ['required', 'in:' . implode(',', FormPayment::TYPES_FORM_PAYMENT)],
        ];
    }

    protected function resource(): string
    {
        return FormPaymentResource::class;
    }
}
