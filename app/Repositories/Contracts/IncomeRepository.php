<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface IncomeRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface IncomeRepository extends RepositoryInterface
{
    public function createWithCharge(array $data, object $obj = null);
}
