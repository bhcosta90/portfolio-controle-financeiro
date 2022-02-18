<?php

namespace Modules\Cobranca\Services;

use Illuminate\Database\Eloquent\Model;
use Modules\Cobranca\Models\ContaReceber;

final class ContaReceberService
{
    use Traits\CobrancaServiceTrait;

    public function __construct(private ContaReceber $repository)
    {
        //
    }

    protected function model(): Model
    {
        return app(ContaReceber::class);
    }

    protected function table(): string
    {
        return $this->model()->getTable();
    }
}
