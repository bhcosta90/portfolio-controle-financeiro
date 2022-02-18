<?php

namespace Modules\Cobranca\Http\Controllers;

use Costa\LaravelPackage\Traits\Support\TableTrait;
use Costa\LaravelPackage\Traits\Web\WebCreateTrait;
use Costa\LaravelPackage\Traits\Web\WebDestroyTrait;
use Costa\LaravelPackage\Traits\Web\WebEditTrait;
use Costa\LaravelPackage\Traits\Web\WebIndexTrait;
use Illuminate\Routing\Controller;
use Modules\Cobranca\Forms\ContaBancariaForm;
use Modules\Cobranca\Services\ContaBancariaService;

class ContaBancariaController extends Controller
{
    use WebIndexTrait, TableTrait, WebCreateTrait, WebDestroyTrait, WebEditTrait;

    protected function view(): string
    {
        return 'cobranca::contabancaria';
    }

    protected function service(): string
    {
        return ContaBancariaService::class;
    }

    protected function getTableColumns(): array
    {
        return [
            '' => [
                'class' => 'min',
                'action' => fn ($obj) => ativo($obj->ativo),
            ],
            'Banco' => fn($obj) => $obj->entidade->nome,
            'Agência' => fn($obj) => $obj->agencia,
            'Conta' => fn($obj) => $obj->conta,
            'Tipo da Conta' => fn($obj) => $obj->tipo,
            'Tipo do Documento' => fn($obj) => $obj->tipo_documento,
            'Documento' => fn($obj) => $obj->documento,
            '_edit' => [
                'action' => fn ($obj) => btnLinkEditIcon(route('cobranca.contabancaria.edit', ['contabancarium' => $obj->uuid, 'tenant' => tenant()])),
                'class' => 'min',
            ],
            '_delete' => [
                'action' => fn ($obj) => btnLinkDelIcon(route('cobranca.contabancaria.destroy', ['contabancarium' => $obj->uuid, 'tenant' => tenant()])),
                'class' => 'min',
            ]
        ];
    }

    protected function form(): string
    {
        return ContaBancariaForm::class;
    }

    protected function routeStore(): string
    {
        return route('cobranca.contabancaria.store', ['tenant' => tenant()]);
    }

    protected function routeUpdate($obj): string
    {
        return route('cobranca.contabancaria.update', ['tenant' => tenant(), 'contabancarium' => $obj->uuid]);
    }

    protected function routeRedirectPostPut(): string
    {
        return route('cobranca.contabancaria.index', ['tenant' => tenant()]);
    }
}
