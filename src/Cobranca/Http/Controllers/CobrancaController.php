<?php

namespace Modules\Cobranca\Http\Controllers;

use Carbon\Carbon;
use Costa\LaravelPackage\Support\FormSupport;
use Costa\LaravelPackage\Traits\Web\WebDestroyTrait;
use Costa\LaravelPackage\Traits\Web\WebEditTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
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

    public function pagarShow($id)
    {

        $obj = $this->getService()->find($id);

        $objForm = app(FormSupport::class);
        $objForm->form = PagamentoForm::class;
        $objForm->model = $obj;
        $objForm->button = 'Enviar';

        $form = $objForm->exec('POST', route('cobranca.cobranca.pagar.store', [tenant(), $id]));

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

        $objForm = app(FormSupport::class);
        $objForm->form = PagamentoForm::class;
        $objForm->model = $obj;
        $data = $this->serialize($objForm->data());

        switch ($obj->cobranca_type) {
            case ContaReceber::class;
                $redirect = route('cobranca.conta.receber.index', $dataRedirect);
                $data['movimento'] = "Conta a receber";
                break;
            case ContaPagar::class;
                $redirect = route('cobranca.conta.pagar.index', $dataRedirect);
                $data['movimento'] = "Conta a pagar";
                break;
            default:
                throw new Exception('Não foi possível redirecionar');
        }

        if ($obj->tipo == Cobranca::$TIPO_DEBITO) {
            $data['valor_total'] *= -1;
        }

        if ($obj->status == Cobranca::$STATUS_PAGO) {
            return redirect($redirect)->with('warning', 'Essa cobrança já foi liquidada');
        }

        $data['valor_cobranca'] = $data['valor_cobranca'];

        if ($data['valor_cobranca'] > $obj->valor_cobranca) {
            throw ValidationException::withMessages(['valor_cobranca' => 'Este valor não pode ser superior a o valor de: ' . $obj->valor_cobranca]);
        }

        $data['user_id'] = $request->user()->id;
        $data['pagamento_type'] = $obj->cobranca_type;
        $data['cobranca_id'] = $obj->id;
        $data['parcela'] = $obj->parcela;
        $data['valor_multa'] = $data['valor_multa'];
        $data['valor_juros'] = $data['valor_juros'];
        $data['valor_desconto'] = $data['valor_desconto'];
        $data['valor_total'] = str()->truncate($data['valor_total']);
        $data['conta_bancaria_id'] = $this->getContaBancariaService()->find($data['conta_bancaria_id'])?->id;
        $data['forma_pagamento_id'] = $this->getFormaPagamentoService()->find($data['forma_pagamento_id'])?->id;
        $data['entidade_id'] = $obj->entidade_id;
        $data['descricao'] = $obj->descricao;
        $data['tipo'] = $obj->tipo;

        return DB::transaction(function () use ($obj, $data, $redirect) {
            $this->getPagamentoService()->store($obj->cobranca_type, $data);

            $dataUpdate = [];

            if ($obj->valor_frequencia == null) {
                $dataUpdate += [
                    'valor_frequencia' => $obj->valor_cobranca,
                ];
            }

            $duplicar = false;
            if (($valorCobranca = $obj->valor_cobranca) == $data['valor_cobranca']) {
                $duplicar = true;
                $dataUpdate += [
                    'status' => Cobranca::$STATUS_PAGO,
                    'valor_original' => null
                ];
            } else {
                $dataUpdate += [
                    'valor_original' => $valorCobranca,
                    'valor_cobranca' => $valorCobranca - $data['valor_cobranca'],
                ];
            }

            $obj->update($dataUpdate);

            if ($duplicar && $obj->frequencia_id) {
                $this->getCobrancaService()->duplicarCobranca($obj);
            }

            return redirect($redirect)->with('success', 'Pagamento realizado com sucesso');
        });
    }

    protected function view(): string
    {
        return 'cobranca::cobranca';
    }

    protected function serializeModel($obj)
    {
        $obj->frequencia_id = $this->getFrequenciaService()->getById($obj->frequencia_id)?->uuid;
        $obj->conta_bancaria_id = $this->getContaBancariaService()->getById($obj->conta_bancaria_id)?->uuid;
        $obj->forma_pagamento_id = $this->getFormaPagamentoService()->getById($obj->forma_pagamento_id)?->uuid;
        $obj->entidade_id = ($objEntidade = $this->getEntidadeService()->getById($obj->entidade_id))?->uuid;
        $obj->fornecedor = $objEntidade?->nome;
        $obj->cliente = $objEntidade?->nome;
        $obj->valor_cobranca = str()->numberEnToBr($obj->valor_cobranca);
        return $obj;
    }

    protected function routeUpdate($obj): string
    {
        return route('cobranca.cobranca.update', ['tenant' => tenant(), 'cobranca' => $obj->uuid]);
    }

    protected function serialize($array)
    {
        $array['valor_cobranca'] = str()->numberBrToEn($array['valor_cobranca']);
        $array['valor_multa'] = str()->numberBrToEn($array['valor_multa'] ?? 0);
        $array['valor_juros'] = str()->numberBrToEn($array['valor_juros'] ?? 0);
        $array['valor_desconto'] = str()->numberBrToEn($array['valor_desconto'] ?? 0);

        if (isset($array['parcelas'])) {
            foreach ($array['parcelas'] as &$rs) {
                $rs['valor'] = str()->numberBrToEn($rs['valor']);
            }
        }

        return $array;
    }

    protected function routeRedirectPostPut($obj = null): string
    {
        $data = [
            'tenant' => tenant(),
            'data_inicio' => (new Carbon)->firstOfMonth()->format('Y-m-d'),
            'data_final' => (new Carbon)->endOfMonth()->format('Y-m-d')
        ];

        switch ($obj[0]->cobranca_type) {
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

    /**
     * @return CobrancaService
     */
    protected function getCobrancaService()
    {
        return app(CobrancaService::class);
    }
}
