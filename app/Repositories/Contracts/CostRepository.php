<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface CostRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface CostRepository extends RepositoryInterface
{
    public function createWithCharge(array $data);
}
