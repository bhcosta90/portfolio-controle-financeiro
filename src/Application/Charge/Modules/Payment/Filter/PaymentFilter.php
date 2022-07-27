<?php

namespace Core\Application\Charge\Modules\Payment\Filter;

use Core\Shared\Abstracts\FilterAbstract;
use DateTime;

class PaymentFilter extends FilterAbstract
{
    public function handle(): array
    {
        return [
            [
                'type' => 'text',
                'label' => 'Name',
                'name' => 'company_name',
                'placeholder' => 'To search for the name of company, please enter here',
            ],
            [
                'type' => 'date_between',
                'label' => 'Due date',
                'name' => 'date',
                'value' => [
                    (new DateTime())->modify('first day of this month')->format('Y-m-d'),
                    (new DateTime())->modify('last day of this month')->format('Y-m-d'),
                ],
            ],
            [
                'type' => 'checkbox',
                'label' => 'Types',
                'name' => 'type',
                'options' => [
                    0 => 'Vencidos',
                    1 => 'Desse mês',
                    2 => 'Próximo mês'
                ],
                'values' => [0, 1],
            ]
        ];
    }
}
