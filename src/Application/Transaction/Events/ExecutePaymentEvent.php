<?php

namespace Core\Application\Transaction\Events;

use Core\Shared\Abstracts\EventAbstract;

class ExecutePaymentEvent extends EventAbstract
{
    public function __construct(
        private string|int $tenant,
        private string|int $id,
    ) {
        //
    }

    public function name(): string
    {
        return 'transaction.execute.' . $this->id;
    }

    public function payload(): array
    {
        return [
            'id' => $this->id,
            'tenant' => $this->tenant,
        ];
    }
}
