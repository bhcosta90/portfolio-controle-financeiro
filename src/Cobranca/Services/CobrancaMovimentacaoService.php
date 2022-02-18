<?php

namespace Modules\Cobranca\Services;

use Modules\Cobranca\Models\Cobranca;

final class CobrancaMovimentacaoService
{
    public static $bancoAtivo;

    public function __construct(private Cobranca $repository)
    {
        //
    }

    public function data($filter = [])
    {
        $this->getBancoAtivo($filter);

        $filter['conta_bancaria_id'] = self::$bancoAtivo['id'];
        return $this->getPagamentoService()->data($filter);
    }

    public function getBancoAtivo($filter = [])
    {
        if (empty($filter['conta_bancaria_id'])) {
            self::$bancoAtivo = $this->getContaBancariaService()->data()->first();
        } else {
            self::$bancoAtivo = $this->getContaBancariaService()->find($filter['conta_bancaria_id']);
        }

        return self::$bancoAtivo;
    }

    /**
     * @return ContaBancariaService
     */
    protected function getContaBancariaService()
    {
        return app(ContaBancariaService::class);
    }

    /**
     * @return PagamentoService
     */
    protected function getPagamentoService()
    {
        return app(PagamentoService::class);
    }
}
