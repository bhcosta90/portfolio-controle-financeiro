<?php

namespace Modules\Cobranca\Http\Controllers;

use Carbon\Carbon;
use Costa\LaravelPackage\Traits\Support\DayWeekTrait;
use Costa\LaravelPackage\Traits\Support\TableTrait;
use Costa\LaravelPackage\Traits\Web\WebCreateTrait;
use Costa\LaravelPackage\Traits\Web\WebDestroyTrait;
use Costa\LaravelPackage\Traits\Web\WebEditTrait;
use Costa\LaravelPackage\Traits\Web\WebIndexTrait;
use Costa\LaravelPackage\Utils\Value;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Cobranca\Forms\FrequenciaForm;
use Modules\Cobranca\Services\FrequenciaService;

class FrequenciaController extends Controller
{
    use WebIndexTrait, TableTrait, WebCreateTrait, WebDestroyTrait, WebEditTrait, DayWeekTrait;

    public function calcular(Request $request)
    {
        $objFrequencia = $this->getService()->find($request->frequencia);
        $objDays = collect(explode('|', $objFrequencia->tipo));

        $values = (new Value())->parcel(new Carbon, str()->numberBrToEn($request->valor), $request->parcel);

        $datas = [];
        for($i = 0; $i <= $request->parcel; $i++){
            $data = (new Carbon())->addDay($objDays->first() * $i);
            $datas[] = [
                'original' => $data->format('Y-m-d'),
                'util' => $this->onlyDayWeek($data, 0, true)->format('Y-m-d'),
            ];
        }

        $formatado = [];
        foreach($values as $k => $v) {
            $formatado[] = $datas[$k] + [
                'value' => [
                    'original' => $v['value'],
                    'real' => str()->numberEnToBr($v['value'])
                ]
            ];
        }

        return [
            'data' => [
                'datas' => $datas,
                'parcelas' => $values,
                'formatado' => $formatado
            ]
        ];

        return view('cobranca::parcela.calcular', [
            'data' => [
                'datas' => $datas,
                'parcelas' => $values,
                'formatado' => $formatado
            ],
        ]);
    }

    protected function view(): string
    {
        return 'cobranca::frequencia';
    }

    protected function service(): string
    {
        return FrequenciaService::class;
    }

    protected function getTableColumns(): array
    {
        return [
            '' => [
                'class' => 'min',
                'action' => fn ($obj) => ativo($obj->ativo),
            ],
            'Nome' => fn ($obj) => $obj->nome,
            'Dias' => function ($obj) {
                $tipoArray = explode('|', $obj->tipo);
                if (count($tipoArray) > 1) {
                    return trans_choice('quantity_days', $tipoArray[0], ['total' => $tipoArray[0]]);
                }
                return __($obj->tipo);
            },
            '_edit' => [
                'action' => fn ($obj) => btnLinkEditIcon(route('cobranca.frequencia.edit', ['frequencium' => $obj->uuid, 'tenant' => tenant()])),
                'class' => 'min',
            ],
            '_delete' => [
                'action' => fn ($obj) => btnLinkDelIcon(route('cobranca.frequencia.destroy', ['frequencium' => $obj->uuid, 'tenant' => tenant()])),
                'class' => 'min',
            ]
        ];
    }

    protected function form(): string
    {
        return FrequenciaForm::class;
    }

    protected function routeStore(): string
    {
        return route('cobranca.frequencia.store', ['tenant' => tenant()]);
    }

    protected function routeUpdate($obj): string
    {
        return route('cobranca.frequencia.update', ['tenant' => tenant(), 'frequencium' => $obj->uuid]);
    }

    protected function routeRedirectPostPut(): string
    {
        return route('cobranca.frequencia.index', ['tenant' => tenant()]);
    }
}
