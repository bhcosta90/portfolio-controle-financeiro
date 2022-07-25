<?php

namespace Core\Application\BankAccount\Modules\Bank\Events;

use Core\Shared\Abstracts\EventAbstract;

class RemoveValueEvent extends EventAbstract
{
    public function __construct(private string|int $id, private float $value) {
        //
    }

    public function name(): string
    {
        return 'bank_account.bank.value.remove.' . $this->id;
    }

    public function payload(): array
    {
        return [
            'id' => $this->id,
            'value' => abs($this->value),
        ];
    }
}
