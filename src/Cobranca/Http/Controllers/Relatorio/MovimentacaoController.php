<?php

namespace Modules\Cobranca\Http\Controllers\Relatorio;

use Carbon\Carbon;
use Costa\LaravelPackage\Support\FormSupport;
use Costa\LaravelPackage\Traits\Support\FormTrait;
use Costa\LaravelTable\TableSimple;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Modules\Cobranca\Forms\Relatorio\MovimentacaoForm;
use Modules\Cobranca\Services\PagamentoService;
use PDF;

class MovimentacaoController extends Controller
{
    public function index(FormBuilder $formBuilder)
    {
        $model = [
            'data_inicio' => (new Carbon)->format('Y-m-d'),
            'data_final' => (new Carbon)->format('Y-m-d'),
        ];

        $form = $formBuilder->create($this->form(), [
            'method' => "GET",
            'url' => route('cobranca.relatorio.movimentacao.filter', tenant()),
            'model' => $model,
            'attr' => [
                'target' => "_blank"
            ],
            'data' => [
                'model' => $model,
            ],
        ]);

        $form->add('btn', 'submit', [
            "attr" => ['class' => 'btn btn-primary btn-action', 'data-label' => 'Filtrar'],
            'label' => __('Filtrar'),
        ]);

        return view('cobranca::relatorio.movimentacao.index', compact('form'));
    }

    public function filter(Request $request)
    {
        $service = app(PagamentoService::class);

        $objForm = app(FormSupport::class);
        $objForm->form = $this->form();
        $dataForm = $objForm->data();

        $data = $service->data($dataForm)->get();
        $bancos = $service->data($dataForm)
            ->select('conta_bancarias.uuid')
            ->join('conta_bancarias', 'pagamentos.conta_bancaria_id', '=', 'conta_bancarias.id')
            ->groupBy('conta_bancarias.uuid')->get();

        $total = 0;
        foreach ($bancos as $rs) {
            $objBanco = $service->data([
                'conta_bancaria_id' => $rs->uuid,
                'order' => 'pagamentos.id'
            ] + $dataForm)
                ->limit(1)
                ->first();

            $total += $objBanco->saldo_atual;
        }

        $ret = [
            'data' => $data,
            'dataInicio' => str()->date($request->data_inicio),
            'dataFinal' => str()->date($request->data_final),
            'dataHumano' => str()->informationDate(new Carbon()),
            'total' => $total,
        ];

        return match ($request->formato) {
            'html' => view('cobranca::relatorio.movimentacao.filter', $ret),
            default => $this->imprimirPDF($ret),
        };
    }

    private function imprimirPDF($params)
    {
        $pdf = PDF::loadView('cobranca::relatorio.movimentacao.filter', $params);
        return $pdf->stream();
    }

    protected function form(): string
    {
        return MovimentacaoForm::class;
    }
}
