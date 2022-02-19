<?php

namespace Modules\Entidade\Http\Controllers;

use Costa\LaravelPackage\Traits\Web\WebDestroyTrait;
use Costa\LaravelPackage\Traits\Web\WebEditTrait;
use Illuminate\Routing\Controller;
use Modules\Entidade\Forms\EntidadeForm;
use Modules\Entidade\Models\Banco;
use Modules\Entidade\Models\Cliente;
use Modules\Entidade\Models\Fornecedor;
use Modules\Entidade\Services\BancoService;
use Modules\Entidade\Services\EntidadeService;

class EntidadeController extends Controller
{
    use WebDestroyTrait, WebEditTrait;

    protected function service(): string
    {
        return EntidadeService::class;
    }

    protected function view(): string
    {
        return 'entidade::entidade';
    }

    protected function form(): string
    {
        return EntidadeForm::class;
    }

    protected function getModelEdit($obj)
    {
        $ret = $obj->toArray();
        $ret['banco_id'] = $this->getBancoService()->getById($ret['banco_id'])->uuid;

        return $ret;
    }

    protected function getActionEdit($id, $obj)
    {
        switch(get_class($obj['model']->entidade)){
            case Banco::class;
                $title = 'Banco';
                break;
            case Fornecedor::class;
                $title = 'Fornecedor';
                break;
            case Cliente::class;
                $title = 'Cliente';
                break;
        }

        return [
            'id' => $id,
            'title' => $title
        ];
    }

    protected function routeUpdate($obj): string
    {
        return route('entidade.entidade.update', ['tenant' => tenant(), 'entidade' => $obj->uuid]);
    }

    protected function routeRedirectPostPut($obj = null): string
    {
        switch (get_class($obj->entidade)) {
            case Banco::class;
                return route('entidade.banco.index', ['tenant' => tenant()]);
            case Fornecedor::class;
                return route('entidade.fornecedor.index', ['tenant' => tenant()]);
            case Cliente::class;
                return route('entidade.cliente.index', ['tenant' => tenant()]);
        }
    }

    /**
     * @return BancoService
     */
    protected function getBancoService()
    {
        return app(BancoService::class);
    }
}
