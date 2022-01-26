<?php

namespace App\Services;

use App\Models\Charge;
use App\Models\Cost;
use App\Models\Income;
use App\Repositories\ChargeRepositoryEloquent as Eloquent;
use App\Repositories\Contracts\ChargeRepository as Contract;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChargeService
{
    private Contract $repository;

    public function __construct(Contract $repository)
    {
        /** @var Eloquent */
        $this->repository = $repository;
    }

    public function getDataIndex()
    {
        return $this->repository;
    }

    public function getBy($uuid)
    {
        return $this->repository->where('uuid', $uuid)->first();
    }

    public function webUpdate($id, $data)
    {
        return $this->repository->update($data, $id);
    }

    public function pay($id, $data)
    {
        $obj = $this->getBy($id);

        return DB::transaction(function () use ($obj, $data) {

            $valueAccount = $data['value'];
            if ($obj->chargeable_type == Cost::class) {
                $valueAccount *= -1;
            }

            $objAccount = $this->getAccountService()->getBy($data['account_id']);

            $this->getAccountService()->updateValue(
                $objAccount->bank_code,
                $objAccount->bank_agency,
                $objAccount->bank_account,
                $objAccount->bank_digit,
                $valueAccount
            );

            return $this->repository->update($data + [
                'status' => Charge::STATUS_PAYED
            ], $obj->id);
        });
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }

    public function resume(int $idUser, $filters = [])
    {
        if (empty($filters['date_start'])) {
            $filters['date_start'] = (new Carbon())->firstOfMonth()->format('Y-m-d');
        }

        if (empty($filters['date_finish'])) {
            $filters['date_finish'] = (new Carbon())->firstOfMonth()->lastOfMonth()->format('Y-m-d');
        }

        if (empty($filters['future'])) {
            $filters['future'] = false;
        }

        $total = [
            'cost' => [
                'total' => 0,
                'due_value' => 0,
            ],
            'income' => [
                'total' => 0,
                'due_value' => 0
            ],
            "account" => [
                'total' => $valorAccount = $this->getAccountService()->myTotal($idUser),
            ]
        ];

        $result = $this->repository->where('user_id', $idUser)
            ->whereBetween('due_date', [$filters['date_start'], $filters['date_finish']])
            ->where('future', false)->get();

        foreach ($result as $rs) {
            switch ($rs->chargeable_type) {
                case Income::class:
                    if ($rs->due_date < Carbon::now()->firstOfMonth()->format('Y-m-d')) {
                        $total['income']['due_value'] += $rs->value;
                    } else {
                        $total['income']['total'] += $rs->value;
                    }
                    break;
                case Cost::class:
                    if ($rs->due_date < Carbon::now()->firstOfMonth()->format('Y-m-d')) {
                        $total['cost']['due_value'] += $rs->value;
                    } else {
                        $total['cost']['total'] += $rs->value;
                    }
                    break;
                default:
                    throw new Exception($rs->chargeable_type . ' do not implemented');
            }
        }

        $total['calculate'] = [
            'total' => $valorAccount - $total['cost']['total'] + $total['income']['total'],
        ];

        foreach ($total as &$rs) {
            $rs += [
                'format' => [
                    'total' => Str::numberEnToBr($rs['total']),
                    'due_value' => Str::numberEnToBr($rs['due_value'] ?? 0),
                ]
            ];
        }

        return $total;
    }

    /**
     * @return AccountService
     */
    protected function getAccountService()
    {
        return app(AccountService::class);
    }
}
