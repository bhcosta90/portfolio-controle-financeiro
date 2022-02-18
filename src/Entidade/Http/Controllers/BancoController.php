<?php

namespace Modules\Entidade\Http\Controllers;

use Costa\LaravelPackage\Traits\Web\WebCreateTrait;
use Costa\LaravelPackage\Traits\Web\WebDestroyTrait;
use Costa\LaravelPackage\Traits\Web\WebEditTrait;
use Costa\LaravelPackage\Traits\Web\WebIndexTrait;
use Illuminate\Routing\Controller;
use Modules\Entidade\Forms\BancoForm;
use Modules\Entidade\Services\BancoService;

class BancoController extends Controller
{
    use WebIndexTrait, WebCreateTrait, Traits\EntidadeTrait, WebDestroyTrait, WebEditTrait;

    protected function getTituloColunaNome(): string
    {
        return 'Nome do Banco';
    }

    protected function view(): string
    {
        return 'entidade::banco';
    }

    protected function service(): string
    {
        return BancoService::class;
    }

    protected function routeStore(): string
    {
        return route('entidade.banco.store', ['tenant' => tenant()]);
    }

    protected function routeUpdate($obj): string
    {
        return route('entidade.banco.update', ['tenant' => tenant(), 'banco' => $obj->uuid]);
    }

    protected function routeRedirectPostPut(): string
    {
        return route('entidade.banco.index', ['tenant' => tenant()]);
    }

    protected function form(): string
    {
        return BancoForm::class;
    }
}
