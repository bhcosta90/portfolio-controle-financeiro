<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface AccountRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface AccountRepository extends RepositoryInterface
{
    public function updateValue($uuid, $value);
}
