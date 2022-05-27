<?php

namespace Costa\Modules\Payment\Repository;

use Costa\Modules\Payment\PaymentEntity;
use Costa\Shareds\Contracts\EntityInterface;

interface PaymentRepositoryInterface
{
    public function find(string $uuid): EntityInterface;

    public function insert(PaymentEntity $entity): EntityInterface;

    public function update(PaymentEntity $entity): EntityInterface;
}
