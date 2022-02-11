<?php

namespace App\Services;

use App\Jobs\RegisterNewChargeRecursiveJob;
use App\Models\Charge;
use App\Models\Cost;
use App\Models\Income;
use App\Models\Parcel;
use App\Repositories\ChargeRepositoryEloquent as Eloquent;
use App\Repositories\Contracts\ChargeRepository as Contract;
use Carbon\Carbon;
use Costa\LaravelPackage\Traits\Support\UserTrait;
use Costa\LaravelPackage\Utils\Value;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Contracts\RepositoryInterface;

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
            'parcel_total' => count($parcels ?? []),
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

    public function webUpdate($data, $id)
    {
        $data['customer_name'] = $data['name'];
        $data['date_start'] = $data['due_date'];
        $data['date_end'] = $data['due_date'];

        if (!empty($data['updated_value'])) {
            $data['value_recurrency'] = $data['value'];
        }

        return $this->repository->update($data, $id);
    }

    public function find($id)
    {
        return $this->repository->where('uuid', $id)->firstOrFail();
    }

    public function pay($obj, $value = null)
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

        $this->updateBalanceInUser($ret->chargeable, $value);

        if ($obj->chargeable instanceof Parcel) {
            $obj->basecharge->charge->touch();
            $this->updateBalanceInUser($obj, $value);

            if ($obj->basecharge->parcelsActive->count() == 0) {
                return $this->pay($obj->basecharge->charge->uuid, 0);
            }

            $this->updateDueDateAndDateStart($obj);
        }

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
                $this->updateDueDateAndDateStart($obj);
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

    private function updateBalanceInUser($obj, $value)
    {
        switch (get_class($obj)) {
            case Income::class:
                return $this->getUser()->increment('balance_value', $value);
            case Cost::class:
                return $this->getUser()->decrement('balance_value', $value);
        }
    }

    /**
     * @return ParcelService
     */
    protected function getParcelService()
    {
        return app(ParcelService::class);
    }
}
