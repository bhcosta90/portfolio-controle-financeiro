<?php

namespace App\Services;

use App\Jobs\RegisterNewChargeRecursiveJob;
use App\Models\Charge;
use App\Models\Cost;
use App\Models\Extract;
use App\Models\Income;
use App\Models\Parcel;
use App\Models\User;
use App\Repositories\ChargeRepositoryEloquent as Eloquent;
use App\Repositories\Contracts\ChargeRepository as Contract;
use Carbon\Carbon;
use Costa\LaravelPackage\Traits\Support\UserTrait;
use Costa\LaravelPackage\Utils\Recursive;
use Costa\LaravelPackage\Utils\Value;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Str;

class ChargeService
{
    use UserTrait;

    private Contract $repository;

    public function __construct(Contract $repository)
    {
        /** @var Eloquent */
        $this->repository = $repository;
    }

    public function data()
    {
        return $this->repository;
    }

    public function store(RepositoryInterface $repositoryInterface, array $data)
    {
        $data['date_start'] = $data['due_date'];
        $data['date_end'] = $data['due_date'];
        $data['customer_name'] = $data['name'];
        $data['recurrency_id'] = $data['recurrency'] > 0 ? $data['recurrency'] : null;

        if ($data['recurrency'] == Charge::$TYPE_PAYMENT_PARCEL) {
            $data['date_start'] = $data['due_date'];
            $date = new Carbon($data['due_date']);
            $parcels = collect(app(Value::class)->parcel($date, $data['value'], $data['parcel']));
            $data['date_end'] = $parcels->last()['due_date'];
        }

        $obj = $repositoryInterface->create([]);

        $this->create($obj, $data + [
            'basecharge_type' => get_class($obj),
            'basecharge_id' => $obj->id,
            'parcel_total' => isset($parcels) ? count($parcels) : null,
            'value_recurrency' => $data['value'],
        ]);

        if (isset($parcels)) {
            $this->getParcelService()->store($obj, $data, $parcels);
        }

        return $obj;
    }

    public function create($obj, array $data)
    {
        return $this->repository->create($data + [
            'chargeable_type' => get_class($obj),
            'chargeable_id' => $obj->id,
        ]);
    }

    public function webUpdate($data, $id, $obj)
    {
        if(isset($data['name'])){
            $data['customer_name'] = $data['name'];
        }

        if(isset($data['due_date'])){
            $data['date_start'] = $data['due_date'];
            $data['date_end'] = $data['due_date'];
        }

        $data['value'] = Str::numberBrToEn($data['value']);
        $data['recurrency_id'] = $data['recurrency'] > 0 ? $data['recurrency'] : null;

        if (!empty($data['updated_value']) || $obj->parcel_total == null) {
            $data['value_recurrency'] = $data['value'];
        }

        if (!empty($data['recurrency']) && $data['recurrency'] > 0) {

            $type = $this->getRecurrencyService()->getById($data['recurrency']);

            /** @var Recursive $objRecursive */
            $dates = app(Recursive::class)->calculate(
                $type->type,
                (new Carbon($data['due_date'])),
                (new Carbon($data['due_date']))->addMonth()->lastOfMonth(),
                ['first_date' => true]
            );

            $data['due_date'] = $dates[0]['date_week'];
        }

        if ($data['recurrency'] == Charge::$TYPE_PAYMENT_PARCEL) {
            $data['date_start'] = $data['due_date'];
            $date = new Carbon($data['due_date']);
            $parcels = collect(app(Value::class)->parcel($date, $data['value'], $data['parcel']));
            $data['date_end'] = $parcels->last()['due_date'];
        }

        $data['parcel_total'] = isset($parcels) ? count($parcels) : null;

        $ret = $this->repository->update($data, $id);

        if (isset($parcels)) {
            $this->getParcelService()->store($obj->basecharge, $data, $parcels);
        }

        return $ret;
    }

    public function find($id)
    {
        return $this->repository->where('uuid', $id)->firstOrFail();
    }

    public function pay($obj, User $user, $value = null)
    {
        if ($value === null) {
            $value = $obj->value;
        }

        if ($obj->chargeable_type == Parcel::class) {
            $totalParcelDoNotPay = $this->repository->where('basecharge_type', $obj->basecharge_type)
                ->where('basecharge_id', $obj->basecharge_id)
                ->where('parcel_actual', '<', $obj->parcel_actual)
                ->whereNull('deleted_at')
                ->where('status', Charge::$STATUS_PENDING)
                ->count();

            if ($totalParcelDoNotPay) {
                throw new Exception(__('Há parcelas pendentes, não pode pagar essa cobrança'), Response::HTTP_BAD_REQUEST);
            }
        }

        $ret = $this->repository->update([
            'value_pay' => $value,
            'status' => Charge::$STATUS_PAYED,
        ], $obj->id);

        if ($obj->chargeable instanceof Parcel) {
            $obj->basecharge->charge->touch();

            if ($obj->basecharge->parcelsActive->count() == 0) {
                return $obj->basecharge->charge->delete();
            }

            $this->updateDueDateAndDateStart($obj);
        }

        $this->getExtractService()->registerExtract($user, $obj->chargeable, $value, Extract::$TYPE_PAYMENT, [
            'value_charge' => $obj->value,
            'name' => $obj->customer_name,
            'resume' => $obj->basecharge->charge->resume,
            'base_type' => $obj->basecharge_type,
            'base_id' => $obj->basecharge_id,
            'parcel' => $obj->parcel_actual ?? null,
            'user' => $user,
        ]);

        if ($obj->recurrency_id) {
            RegisterNewChargeRecursiveJob::dispatch($obj);
        }

        return $ret;
    }

    public function delete($id, $obj)
    {
        DB::beginTransaction();

        try {
            $ret = $this->repository->delete($id);
            if ($obj->chargeable instanceof Parcel) {
                $ret = $this->updateDueDateAndDateStart($obj);
                if (empty($ret)) {
                    $obj->basecharge->charge->delete();
                }
            }
            DB::commit();
        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }

        return $ret;
    }

    public function getApiOverdue($idUser, $data)
    {
        return $this->getDefaultApiQuery($idUser, $data)
            ->where('due_date', '<', $data['date_start'])
            ->sum('value');
    }

    public function getApiIncome($idUser, $data)
    {
        return $this->getDefaultApiQuery($idUser, $data)
            ->where(function ($q) {
                $q->where('chargeable_type', Income::class)
                    ->orWhere('basecharge_type', Income::class);
            })
            ->whereBetween('due_date', [$data['date_start'], $data['date_end']])
            ->sum('value');
    }

    public function getApiCost($idUser, $data)
    {
        return $this->getDefaultApiQuery($idUser, $data)
            ->where(function ($q) {
                $q->where('chargeable_type', Cost::class)
                    ->orWhere('basecharge_type', Cost::class);
            })
            ->whereBetween('due_date', [$data['date_start'], $data['date_end']])
            ->sum('value');
    }

    public function getApiResume($idUser, $data)
    {
        $valueCost = $this->getApiCost($idUser, $data);
        $valueIncome = $this->getApiIncome($idUser, $data);

        return $data['balance'] + $valueIncome - $valueCost;
    }

    public function getApiOverdueQuantity($idUser, $data)
    {
        return $this->getDefaultApiQuery($idUser, $data)
            ->where('due_date', '<', $data['date_start'])
            ->count();
    }

    public function getCustomers($idUser, $customer){
        return $this->repository->whereNull('deleted_at')
            ->select([
                'customer_name as name',
            ])
            ->where('customer_name', 'like', "%{$customer}%")
            ->groupBy('customer_name')
            ->orderBy('customer_name')
            ->whereIn('user_id', $idUser)
            ->get();
    }

    private function updateDueDateAndDateStart($obj)
    {
        $nextCharge = $this->repository->where('basecharge_type', $obj->basecharge_type)
            ->where('basecharge_id', $obj->basecharge_id)
            ->where('parcel_actual', '>', $obj->parcel_actual)
            ->whereNull('deleted_at')
            ->where('status', Charge::$STATUS_PENDING)
            ->first();

        if ($nextCharge) {
            return $this->repository->update([
                'due_date' => $nextCharge->due_date,
                'date_start' => $nextCharge->due_date
            ], $obj->basecharge->charge->id);
        }
    }

    private function getDefaultApiQuery($idUser, $data)
    {
        return $this->repository
            ->where(function ($q) {
                $q->whereNull('parcel_total');
                $q->orWhere(function ($q) {
                    $q->where('chargeable_type', Parcel::class);
                });
            })
            ->where('status', Charge::$STATUS_PENDING)
            ->whereNull('value_pay')
            ->whereNull('deleted_at')
            ->where('user_id', $idUser);
    }

    /**
     * @return ParcelService
     */
    protected function getParcelService()
    {
        return app(ParcelService::class);
    }

    /**
     * @return ExtractService
     */
    protected function getExtractService()
    {
        return app(ExtractService::class);
    }

    /**
     * @return RecurrencyService
     */
    protected function getRecurrencyService()
    {
        return app(RecurrencyService::class);
    }
}
