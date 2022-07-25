<?php

namespace Core\Application\BankAccount\Modules\Bank\Filter;

use Core\Shared\Abstracts\FilterAbstract;

class BankFilter extends FilterAbstract
{
    public function handle(): array
    {
        return [
            [
                'type' => 'text',
                'label' => 'Name',
                'name' => 'customer_name',
                'placeholder' => 'To search for the name of customer, please enter here',
            ]
        ];
    }
}
