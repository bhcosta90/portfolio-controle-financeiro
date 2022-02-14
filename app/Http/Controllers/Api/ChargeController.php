<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ChargeService;
use Costa\LaravelPackage\Traits\Support\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChargeController extends Controller
{
    use ServiceTrait;

    public function home(Request $request)
    {
        $method = Str::camel("get_api_" . $request->action);
        $data = $request->all() + [
            'balance' => $request->user()->balance_value,
        ];

        return [
            'data' => [
                'action' => $request->action,
                'value' => $value = Str::truncate($this->getService()->$method($request->user()->id, $data)),
                'format' => 'R$' . Str::numberEnToBr($value),
            ]
        ];
    }

    public function customer(Request $request){
        $data = $this->getService()->getCustomers($request->user()->getSharedIdUser(), $request->search)->toArray();
        $dataResult = array_map(fn ($ret) => ['id' => $ret['name'], 'text' => $ret['name']] + $ret, $data);

        array_push($dataResult, ['id' => 0, 'id_user' => null, 'text' => $request->search]);

        return [
            'results' => $dataResult,
        ];
    }

    protected function service(): string
    {
        return ChargeService::class;
    }
}
