<?php

namespace Modules\Entidade\Services;

use Illuminate\Database\Eloquent\Model;
use Modules\Entidade\Models\Banco;

final class BancoService
{
    public function __construct(private Banco $repository)
    {
        //
    }

    use Traits\EntidadeTrait;

    protected function model(): Model
    {
        return app(Banco::class);
    }

    public function pluck()
    {
        return $this->data()->where('ativo', 1)->get()->pluck('entidade.nome', 'entidade.id')->toArray();
    }
}
