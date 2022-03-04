<?php

namespace Modules\Cobranca\Http\Controllers;

use Costa\LaravelPackage\Traits\Support\TableTrait;
use Costa\LaravelPackage\Traits\Web\WebCreateTrait;
use Costa\LaravelPackage\Traits\Web\WebDestroyTrait;
use Costa\LaravelPackage\Traits\Web\WebEditTrait;
use Costa\LaravelPackage\Traits\Web\WebIndexTrait;
use Illuminate\Routing\Controller;
use Modules\Cobranca\Forms\FormaPagamentoForm;
use Modules\Cobranca\Services\FormaPagamentoService;

class FormaPagamentoController extends Controller
{
    use WebIndexTrait, TableTrait, WebCreateTrait, WebDestroyTrait, WebEditTrait;

    protected function view(): string
    {
        return 'cobranca::formapagamento';
    }

    protected function service(): string
    {
        return FormaPagamentoService::class;
    }

    protected function getTableColumns(): array
    {
        return [
            '' => [
                'class' => 'min',
                'action' => fn ($obj) => ativo($obj->ativo),
            ],
            'Nome' => fn ($obj) => $obj->nome,
            '_edit' => [
                'action' => fn ($obj) => btnLinkEditIcon(route('cobranca.formapagamento.edit', ['formapagamento' => $obj->uuid, 'tenant' => tenant()])),
                'class' => 'min',
            ],
            '_delete' => [
                'action' => fn ($obj) => empty($obj->tipo) ? btnLinkDelIcon(route('cobranca.formapagamento.destroy', ['formapagamento' => $obj->uuid, 'tenant' => tenant()])) : '-',
                'class' => 'min',
            ]
        ];
    }

    protected function form(): string
    {
        return FormaPagamentoForm::class;
    }

    protected function routeStore(): string
    {
        return route('cobranca.formapagamento.store', ['tenant' => tenant()]);
    }

    protected function routeUpdate($obj): string
    {
        return route('cobranca.formapagamento.update', ['tenant' => tenant(), 'formapagamento' => $obj->uuid]);
    }

    protected function routeRedirectPostPut(): string
    {
        return route('cobranca.formapagamento.index', ['tenant' => tenant()]);
    }
}
