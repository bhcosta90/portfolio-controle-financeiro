<?php

namespace Core\Application\Charge\Modules\Receive\Filter;

use Core\Shared\Abstracts\FilterAbstract;
use DateTime;

class ReceiveFilter extends FilterAbstract
{
    public function handle(): array
    {
        return [
            [
                'type' => 'text',
                'label' => 'Name',
                'name' => 'customer_name',
                'placeholder' => 'To search for the name of customer, please enter here',
            ],
            [
                'type' => 'date_between',
                'label' => 'Due date',
                'name' => 'date',
                'value' => [
                    (new DateTime())->modify('first day of this month')->format('Y-m-d'),
                    (new DateTime())->modify('last day of this month')->format('Y-m-d'),
                ],
            ]
        ];
    }
}
