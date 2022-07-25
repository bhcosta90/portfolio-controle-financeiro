<?php

namespace Core\Application\Relationship\Modules\Customer\Filters;

use Core\Shared\Abstracts\FilterAbstract;

class CustomerFilter extends FilterAbstract
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
