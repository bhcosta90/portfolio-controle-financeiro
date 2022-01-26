<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\AccountRepository;
use App\Models\Account;
use App\Validators\AccountValidator;
use Illuminate\Support\Facades\DB;

/**
 * Class AccountRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class AccountRepositoryEloquent extends BaseRepository implements AccountRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Account::class;
    }

    public function updateValue($bankCode, $bankAgency, $bankAccount, $bankDigit, $value)
    {
        $obj = $this->where('bank_code', $bankCode)
            ->where('bank_agency', $bankAgency)
            ->where('bank_account', $bankAccount)
            ->where('bank_digit', $bankDigit)
            ->first();

        $obj->increment('value', $value);
        $obj->save();
        return $obj;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
