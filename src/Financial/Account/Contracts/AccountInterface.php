<?php

namespace Core\Financial\Account\Contracts;

use Core\Shared\Abstracts\EntityAbstract;

interface AccountInterface
{
    public function getEntityAccount(object $input): EntityAbstract;
}
