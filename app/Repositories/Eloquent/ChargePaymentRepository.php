<?php

namespace App\Repositories\Eloquent;

use App\Models\Charge;
use App\Models\ChargePayment;
use App\Models\Recurrence;
use Costa\Modules\Charge\Entities\ChargePaymentEntity;
use Costa\Modules\Charge\Repository\ChargePaymentRepositoryInterface;

class ChargePaymentRepository extends Abstracts\ChargeRepository implements ChargePaymentRepositoryInterface
{
    protected string $entity = ChargePaymentEntity::class;
    protected string $table = 'charge_payments';

    public function __construct(
        protected ChargePayment $model,
        protected Charge $charge,
        protected Recurrence $recurrence,
    ) {
        //
    }
}
