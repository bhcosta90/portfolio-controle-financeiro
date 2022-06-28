<?php

namespace Core\Financial\Payment\Repository;

use Core\Shared\Interfaces\RepositoryInterface;

interface PaymentRepositoryInterface extends RepositoryInterface
{
    public function findPaymentExecuteByDate(string $date);
}
