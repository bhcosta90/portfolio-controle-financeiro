<?php

namespace Modules\Entidade\Http\Controllers;

use Costa\LaravelPackage\Traits\Web\WebCreateTrait;
use Costa\LaravelPackage\Traits\Web\WebIndexTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Entidade\Forms\FornecedorForm;
use Modules\Entidade\Services\FornecedorService;

class FornecedorController extends Controller
{
    use WebIndexTrait, WebCreateTrait, Traits\EntidadeTrait;

    protected function getTituloColunaNome(): string
    {
        return 'Nome do Fornecedor';
    }

    protected function view(): string
    {
        return 'entidade::fornecedor';
    }

    protected function service(): string
    {
        return FornecedorService::class;
    }

    protected function routeStore(): string
    {
        return route('entidade.fornecedor.store', ['tenant' => tenant()]);
    }

    protected function routeRedirectPostPut(): string
    {
        return route('entidade.fornecedor.index', ['tenant' => tenant()]);
    }

    protected function form(): string
    {
        return FornecedorForm::class;
    }

    public function search(Request $request)
    {
        $data = $this->getService()->data(['nome' => $request->search])->get();
        $dataResult = $data->map(fn($rs) => ['id' => $rs->entidade->uuid, 'text' => $rs->entidade->nome]);

        return [
            'results' => $dataResult,
        ];
    }
}
