<?php

namespace Core\Application\Charge\Modules\Recurrence\Filter;

use Core\Shared\Abstracts\FilterAbstract;

class RecurrenceFilter extends FilterAbstract
{
    public function handle(): array
    {
        return [
            [
                'type' => 'text',
                'label' => 'Name',
                'name' => 'name',
                'placeholder' => 'To search for the name, please enter here',
            ]
        ];
    }
}
