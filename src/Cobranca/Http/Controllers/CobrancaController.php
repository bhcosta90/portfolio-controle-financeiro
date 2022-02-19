<?php

namespace Modules\Cobranca\Http\Controllers;

use Carbon\Carbon;
use Costa\LaravelPackage\Traits\Web\WebDestroyTrait;
use Costa\LaravelPackage\Traits\Web\WebEditTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Modules\Cobranca\Forms\CobrancaForm;
use Modules\Cobranca\Forms\PagamentoForm;
use Modules\Cobranca\Models\Cobranca;
use Modules\Cobranca\Models\ContaPagar;
use Modules\Cobranca\Models\ContaReceber;
use Modules\Cobranca\Models\Pagamento;
use Modules\Cobranca\Services\CobrancaService;
use Modules\Cobranca\Services\ContaBancariaService;
use Modules\Cobranca\Services\FormaPagamentoService;
use Modules\Cobranca\Services\FrequenciaService;
use Modules\Cobranca\Services\PagamentoService;
use Modules\Entidade\Services\EntidadeService;

class CobrancaController extends Controller
{
    use WebEditTrait, WebDestroyTrait;

    public function pagarShow($id){

        $obj = $this->getService()->find($id);

        $form = $this->transformInFormBuilder(
            'POST',
            route('cobranca.cobranca.pagar.store', [tenant(), $id]),
            $obj,
            'Enviar',
            PagamentoForm::class
        );

        return view('cobranca::cobranca.pagamento', compact('form', 'obj'));
    }

    public function pagarStore($id, Request $request)
    {
        $dataRedirect = [
            'tenant' => tenant(),
            'data_inicio' => (new Carbon)->firstOfMonth()->format('Y-m-d'),
            'data_final' => (new Carbon)->endOfMonth()->format('Y-m-d')
        ];

        $obj = $this->getService()->find($id);

        $data = $this->getDataForm(PagamentoForm::class, $obj);

        switch ($obj->cobranca_type) {
            case ContaReceber::class;
                $redirect = route('cobranca.conta.receber.index', $dataRedirect);
                $data['movimento'] = "Conta a receber";
                $tipo = Pagamento::$TIPO_RECEBIMENTO;
                break;
            case ContaPagar::class;
                $redirect = route('cobranca.conta.pagar.index', $dataRedirect);
                $data['movimento'] = "Conta a pagar";
                $data['valor_total'] *= -1;
                $tipo = Pagamento::$TIPO_PAGAMENTO;
                break;
            default:
                throw new Exception('Não foi possível redirecionar');
        }

        if ($obj->status == Cobranca::$STATUS_PAGO) {
            return redirect($redirect)->with('success', 'Essa cobrança já foi liquidada');
        }

        $data['valor_cobranca'] = str()->numberBrToEn($data['valor_cobranca']);

        if($data['valor_cobranca'] > str()->numberBrToEn($obj->valor_cobranca)){
            throw ValidationException::withMessages(['valor_cobranca' => 'Este valor não pode ser superior a o valor de: ' . $obj->valor_cobranca]);
        }

        $data['user_id'] = $request->user()->id;
        $data['pagamento_type'] = $obj->cobranca_type;
        $data['cobranca_id'] = $obj->id;
        $data['parcela'] = $obj->parcela;
        $data['valor_multa'] = str()->numberBrToEn($data['valor_multa']);
        $data['valor_juros'] = str()->numberBrToEn($data['valor_juros']);
        $data['valor_desconto'] = str()->numberBrToEn($data['valor_desconto']);
        $data['valor_total'] = str()->truncate($data['valor_total']);
        $data['conta_bancaria_id'] = $this->getContaBancariaService()->find($data['conta_bancaria_id'])?->id;
        $data['forma_pagamento_id'] = $this->getFormaPagamentoService()->find($data['forma_pagamento_id'])?->id;
        $data['entidade_id'] = $obj->entidade_id;
        $data['descricao'] = $obj->descricao;
        $data['tipo'] = $tipo;

        $this->getPagamentoService()->store($obj->cobranca_type, $data);

        if (($valorCobranca = str()->numberBrToEn($obj->valor_cobranca)) == $data['valor_cobranca']) {
            $obj->update([
                'status' => Cobranca::$STATUS_PAGO,
                'valor_original' => null
            ]);
        } else {
            $obj->update([
                'valor_original' => $valorCobranca,
                'valor_cobranca' => $valorCobranca - $data['valor_cobranca'],
            ]);
        }

        return redirect($redirect)->with('success', 'Pagamento realizado com sucesso');
    }

    protected function view(): string
    {
        return 'cobranca::cobranca';
    }

    protected function getModelEdit($obj)
    {
        $ret = $obj->toArray();
        $ret['frequencia_id'] = $this->getFrequenciaService()->getById($ret['frequencia_id'])?->uuid;
        $ret['conta_bancaria_id'] = $this->getContaBancariaService()->getById($ret['conta_bancaria_id'])?->uuid;
        $ret['forma_pagamento_id'] = $this->getFormaPagamentoService()->getById($ret['forma_pagamento_id'])?->uuid;
        $ret['entidade_id'] = ($objEntidade = $this->getEntidadeService()->getById($ret['entidade_id']))?->uuid;
        $ret['fornecedor'] = $objEntidade?->nome;
        $ret['cliente'] = $objEntidade?->nome;

        return $ret;
    }

    protected function routeUpdate($obj): string
    {
        return route('cobranca.cobranca.update', ['tenant' => tenant(), 'cobranca' => $obj->uuid]);
    }

    protected function routeRedirectPostPut($obj = null): string
    {
        $data = [
            'tenant' => tenant(),
            'data_inicio' => (new Carbon)->firstOfMonth()->format('Y-m-d'),
            'data_final' => (new Carbon)->endOfMonth()->format('Y-m-d')
        ];

        switch($obj[0]->cobranca_type) {
            case ContaReceber::class;
                return route('cobranca.conta.receber.index', $data);
            case ContaPagar::class;
                return route('cobranca.conta.pagar.index', $data);
        }

        throw new Exception('Não foi possível redirecionar');
    }

    protected function service(): string
    {
        return CobrancaService::class;
    }

    protected function form(): string
    {
        return CobrancaForm::class;
    }

    /**
     * @return FrequenciaService
     */
    protected function getFrequenciaService()
    {
        return app(FrequenciaService::class);
    }

    /**
     * @return ContaBancariaService
     */
    protected function getContaBancariaService()
    {
        return app(ContaBancariaService::class);
    }

    /**
     * @return EntidadeService
     */
    protected function getEntidadeService()
    {
        return app(EntidadeService::class);
    }

    /**
     * @return PagamentoService
     */
    protected function getPagamentoService()
    {
        return app(PagamentoService::class);
    }

    /**
     * @return FormaPagamentoService
     */
    protected function getFormaPagamentoService()
    {
        return app(FormaPagamentoService::class);
    }
}
