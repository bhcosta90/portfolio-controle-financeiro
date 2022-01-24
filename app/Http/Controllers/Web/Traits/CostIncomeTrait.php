<?php

namespace App\Http\Controllers\Web\Traits;

use Illuminate\Support\Str;
use Carbon\Carbon;
use Costa\LaravelTable\TableSimple;

trait CostIncomeTrait
{
    protected function costIncomeTraitGetData($serviceData)
    {
        $dataAtual = Carbon::now()->format('Y-m-d');

        /** @var TableSimple $table */
        $table = app(TableSimple::class);
        $table->setData($this->transformData($serviceData));
        $table->setColumns(false);
        $table->setAddColumns([
            __('Customer name') => function($model){
                $icon = "<i class='fa fa-user text-info'></i>";
                $ret = "{$icon} {$model->charge->customer_name}";

                if (!empty($model->charge->resume)) {
                    $ret .= '<br /><small class="text-muted">' . $model->charge->resume . '</small>';
                }

                return $ret;

            },
            __('Value') => function ($model) use ($dataAtual) {
                $ret = "R$ " . Str::numberEnToBr($model->charge->value);
                if ($model->charge->parcel_total > 1) {
                    $ret .= "<br /><span class='text-muted'>" . __('Parcela: :atual/:total', [
                        'atual' => $model->charge->parcel_actual,
                        'total' => $model->charge->parcel_total,
                    ]) . "</span>";
                } else if ($model->charge->parcel_total == $model->charge->parcel_actual && $model->charge->parcel_total == 1) {
                    $texto = __('Á Vista');
                    $dataVencimento = (new Carbon($model->data_vencimento));
                    if ($dataVencimento->format('Y-m-d') < $dataAtual) {
                        $texto = __('Em atraso');
                    }
                    $ret .= "<br /><span class='text-muted'>" . $texto . "</span>";
                }

                if ($model->charge->type) {
                    $ret .= '<br /><span class="text-muted">' . __('Recurrency: ' . $model->charge->type . '') . '</span>';
                }
                return $ret;
            }
,
            __('Due date') => function ($model) use ($dataAtual) {
                $ret = '';
                $dataVencimento = (new Carbon($model->charge->due_date));
                $daysDiferent = $daysDiferentReal = $dataVencimento->diffInDays($dataAtual);

                $atraso = "";
                $ret .= $dataVencimento->format('d/m/Y');
                if ($dataVencimento->format('Y-m-d') < $dataAtual) {
                    $atraso = '<i class="far fa-clock text-danger"></i>';
                    $daysDiferentReal *= -1;
                } else if ($dataVencimento->format('Y-m-d') == $dataAtual) {
                    $atraso = '<i class="far fa-clock text-warning"></i>';
                }

                $ret .= "<span class='text-muted'>";
                $ret .= "<br />{$atraso} " . trans_choice("vencimento", $daysDiferentReal, ['total' => $daysDiferent]);
                $ret .= '</span>';

                return $ret;
            },
        ]);
        return $table->run();
    }

}
