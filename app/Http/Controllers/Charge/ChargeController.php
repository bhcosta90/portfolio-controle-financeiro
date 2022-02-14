<?php

namespace App\Http\Controllers\Charge;

use App\Forms\Charge\ChargeForm;
use App\Forms\Charge\ChargePayForm;
use App\Http\Controllers\Controller;
use App\Models\Charge;
use App\Models\Cost;
use App\Models\Income;
use App\Models\Parcel;
use App\Services\ChargeService;
use Costa\LaravelPackage\Traits\Support\ServiceTrait;
use Costa\LaravelPackage\Traits\Web\WebDestroyTrait;
use Costa\LaravelPackage\Traits\Web\WebEditTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Str;

class ChargeController extends Controller
{
    use ServiceTrait, WebDestroyTrait, WebEditTrait;

    protected function service(): string
    {
        return ChargeService::class;
    }

    protected function view(): string
    {
        return 'charge';
    }

    protected function getActionEdit(): array
    {
        $token = request()->user()->getLoginCustomer()->plainTextToken;

        return [
            'token' => $token,
        ];
    }

    protected function getModelEdit($obj)
    {
        $ret = $obj->toArray();
        $ret['name'] = $obj->customer_name;
        $ret['value'] = Str::numberEnToBr($ret['value']);
        return $ret;
    }

    protected function form(): string
    {
        return ChargeForm::class;
    }

    protected function routeUpdate($obj): string
    {
        return route('charge.update', $obj->uuid);
    }

    protected function routeRedirectPostPut($obj = null): string
    {
        switch(get_class($obj->basecharge)){
            case Income::class:
                return route('income.index');
            case Cost::class:
                return route('cost.index');
            case Parcel::class:
                return $this->routeRedirectPostPut($obj->chargeable->chargeParcel);
        }

        throw new Exception('routeRedirectPostPut do not return');
    }

    public function pay($id)
    {
        $obj = $this->getService()->find($id);

        $title = 'Pagar';
        switch (get_class($obj->basecharge)) {
            case Income::class:
                $title = 'Receber';
                break;
        }

        $form = $this->transformInFormBuilder('PUT', route('charge.pay.update', $id), [], __($title), ChargePayForm::class);
        return view('charge.pay', compact('form', 'obj', 'title'));
    }

    public function payUpdate($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $obj = $this->getService()->find($id);

            if($obj->status == Charge::$STATUS_PAYED && $obj->value_pay > 0){
                return redirect($this->routeRedirectPostPut($obj))->with('error', __('Cobrança já está liquidada'));
            }

            $data = $this->getDataForm(ChargePayForm::class);
            $this->getService()->pay($obj, $request->user(), $data['value_pay']);
            DB::commit();
            return redirect($this->routeRedirectPostPut($obj))->with('success', __('Cobrança paga com sucesso'));
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }


    }
}
