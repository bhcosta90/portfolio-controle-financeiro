<?php

namespace Modules\Entidade\Services;

use Illuminate\Database\Eloquent\Model;
use Modules\Entidade\Models\Cliente;

final class ClienteService
{
    public function __construct(private Cliente $repository)
    {
        //
    }

    use Traits\EntidadeTrait;

    protected function model(): Model
    {
        return app(Cliente::class);
    }
}
