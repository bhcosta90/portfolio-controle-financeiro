<?php

namespace App\Repositories\Eloquent;

use App\Models\Charge;
use App\Models\ChargeReceive;
use App\Models\Recurrence;
use Costa\Modules\Charge\Entities\ChargeReceiveEntity;
use Costa\Modules\Charge\Repository\ChargeReceiveRepositoryInterface;

class ChargeReceiveRepository extends Abstracts\ChargeRepository implements ChargeReceiveRepositoryInterface
{
    protected string $entity = ChargeReceiveEntity::class;
    protected string $table = 'charge_receives';

    public function __construct(
        protected ChargeReceive $model,
        protected Charge $charge,
        protected Recurrence $recurrence,
    ) {
    }

    
}
