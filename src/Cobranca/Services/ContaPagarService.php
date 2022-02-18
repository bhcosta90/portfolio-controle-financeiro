<?php

namespace Modules\Cobranca\Services;

use Illuminate\Database\Eloquent\Model;
use Modules\Cobranca\Models\ContaPagar;

final class ContaPagarService
{
    use Traits\CobrancaServiceTrait;

    public function __construct(private ContaPagar $repository)
    {
        //
    }

    protected function model(): Model
    {
        return app(ContaPagar::class);
    }

    protected function table(): string
    {
        return $this->model()->getTable();
    }
}
