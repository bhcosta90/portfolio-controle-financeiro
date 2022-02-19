<?php

namespace Modules\Cobranca\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Modules\Cobranca\Forms\ContaPagarForm;
use Modules\Cobranca\Services\ContaPagarService;

class ContaPagarController extends Controller
{
    use Traits\CobrancaControllerTrait;

    protected function getTableColumns(): array
    {
        return [
            'Fornecedor' => fn ($obj) => $this->estaVencido($obj->cobranca, $obj->cobranca->entidade?->nome ?: 'Sem fornecedor'),
            'Saída' => fn ($obj) => $this->estaVencido($obj->cobranca, $obj->cobranca->conta_bancaria_id ? $obj->cobranca->conta_bancaria->nome_select : '-'),
            'Descrição' => fn ($obj) => $this->estaVencido($obj->cobranca, $obj->cobranca->descricao_parcela),
            'Vencimento' => fn ($obj) => $this->estaVencido($obj->cobranca, str()->date($obj->cobranca->data_vencimento)),
            'Valor' => fn ($obj) => $this->estaVencido($obj->cobranca, "<span>{$obj->cobranca->valor_cobranca}</span> <small class='text-muted'>" . ($obj->cobranca->valor_original ? "({$obj->cobranca->valor_original})" : '') . "</small>"),
            'Frequência' => fn ($obj) => $this->estaVencido($obj->cobranca, $obj->cobranca->frequencia_id ? $obj->cobranca->frequencia->nome : 'Uma vez'),
            '_pagar' => [
                'action' => fn ($obj) => btnLinkIcon(route('cobranca.cobranca.pagar.show', ['cobranca' => $obj->cobranca->uuid, 'tenant' => tenant()]), 'fas fa-check', '', 'btn-sm btn-outline-success'),
                'class' => 'min',
            ],
            '_edit' => [
                'action' => fn ($obj) => btnLinkEditIcon(route('cobranca.cobranca.edit', ['cobranca' => $obj->cobranca->uuid, 'tenant' => tenant()])),
                'class' => 'min',
            ],
            '_delete' => [
                'action' => fn ($obj) => btnLinkDelIcon(route('cobranca.cobranca.destroy', ['cobranca' => $obj->cobranca->uuid, 'tenant' => tenant()])),
                'class' => 'min',
            ],
        ];
    }

    protected function view(): string
    {
        return 'cobranca::contapagar';
    }

    protected function service(): string
    {
        return ContaPagarService::class;
    }

    protected function form(): string
    {
        return ContaPagarForm::class;
    }

    protected function routeStore(): string
    {
        return route('cobranca.conta.pagar.store', [
            'tenant' => tenant(),
            'data_inicio' => (new Carbon)->firstOfMonth()->format('Y-m-d'),
            'data_final' => (new Carbon)->endOfMonth()->format('Y-m-d')
        ]);
    }

    protected function routeRedirectPostPut(): string
    {
        return route('cobranca.conta.pagar.index', [
            'tenant' => tenant(),
            'data_inicio' => (new Carbon)->firstOfMonth()->format('Y-m-d'),
            'data_final' => (new Carbon)->endOfMonth()->format('Y-m-d')
        ]);
    }

    protected function serialize($array)
    {
        $array['valor_cobranca'] = str()->numberBrToEn($array['valor_cobranca']);
        if (isset($array['parcelas'])) {
            foreach ($array['parcelas'] as &$rs) {
                $rs['valor'] = str()->numberBrToEn($rs['valor']);
            }
        }

        return $array;
    }
}
