<?php

namespace Modules\Entidade\Models\Traits;

use Modules\Entidade\Models\Entidade;

trait EntidadeTrait
{
    public function entidade()
    {
        return $this->morphOne(Entidade::class, 'entidade');
    }
}
