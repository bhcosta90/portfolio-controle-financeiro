<?php

namespace Modules\Entidade\Services;

use Illuminate\Database\Eloquent\Model;
use Modules\Entidade\Models\Fornecedor;

final class FornecedorService
{
    public function __construct(private Fornecedor $repository)
    {
        //
    }

    use Traits\EntidadeTrait;

    protected function model(): Model
    {
        return app(Fornecedor::class);
    }
}
