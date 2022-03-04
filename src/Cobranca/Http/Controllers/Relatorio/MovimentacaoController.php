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

    public function filter(Request $request){
        $service = app(PagamentoService::class);

        $objForm = app(FormSupport::class);
        $objForm->form = $this->form();
        $dataForm = $objForm->data();

        $data = $service->data($dataForm)->get();
        $total = $service->data($dataForm)->sum('saldo_atual');

        $ret = [
            'data' => $data,
            'dataInicio' => str()->date($request->data_inicio),
            'dataFinal' => str()->date($request->data_final),
            'dataHumano' => str()->informationDate(new Carbon()),
            'total' => $total,
        ];

        return match($request->formato){
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
