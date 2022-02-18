<?php

namespace Modules\Entidade\Http\Controllers;

use Costa\LaravelPackage\Traits\Web\WebCreateTrait;
use Costa\LaravelPackage\Traits\Web\WebDestroyTrait;
use Costa\LaravelPackage\Traits\Web\WebEditTrait;
use Costa\LaravelPackage\Traits\Web\WebIndexTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Entidade\Forms\ClienteForm;
use Modules\Entidade\Services\ClienteService;

class ClienteController extends Controller
{
    use WebIndexTrait, WebCreateTrait, Traits\EntidadeTrait, WebDestroyTrait;

    protected function getTituloColunaNome(): string
    {
        return 'Nome do Cliente';
    }

    protected function view(): string
    {
        return 'entidade::cliente';
    }

    protected function service(): string
    {
        return ClienteService::class;
    }

    protected function routeStore(): string
    {
        return route('entidade.cliente.store', ['tenant' => tenant()]);
    }

    protected function routeRedirectPostPut(): string
    {
        return route('entidade.cliente.index', ['tenant' => tenant()]);
    }

    protected function form(): string
    {
        return ClienteForm::class;
    }

    public function search(Request $request)
    {
        $data = $this->getService()->data(['nome' => $request->search])->get();
        $dataResult = $data->map(fn ($rs) => ['id' => $rs->entidade->uuid, 'text' => $rs->entidade->nome]);

        return [
            'results' => $dataResult,
        ];
    }
}
