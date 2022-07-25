<?php

namespace Core\Application\Relationship\Modules\Company\Filters;

use Core\Shared\Abstracts\FilterAbstract;

class CompanyFilter extends FilterAbstract
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
