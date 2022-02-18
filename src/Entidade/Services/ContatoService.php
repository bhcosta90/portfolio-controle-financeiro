<?php

namespace Modules\Entidade\Services;

use Modules\Entidade\Models\Contato;

final class ContatoService
{
    public function __construct(private Contato $repository)
    {
        //
    }

    use Traits\EntidadeTrait;
}
