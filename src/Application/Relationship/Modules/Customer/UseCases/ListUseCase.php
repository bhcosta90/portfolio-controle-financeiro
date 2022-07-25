<?php

namespace Core\Application\Relationship\Modules\Customer\UseCases;

use Core\Application\Relationship\Modules\Customer\Filters\CustomerFilter;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository as Repo;
use Core\Shared\UseCases\List\ListInput;

class ListUseCase
{
    public function __construct(
        private Repo $repository,
        private CustomerFilter $filter,
    ) {
        //
    }

    public function handle(ListInput $input): DTO\List\Output
    {
        $result = $this->repository;

        if (!empty($input->filter['name'])) {
            $result->filterByName($input->filter['name']);
        }

        $result = $result->paginate(filter: $input->filter, page: $input->page, totalPage: $input->total);
        return new DTO\List\Output(
            items: $result->items(),
            total: $result->total(),
            last_page: $result->lastPage(),
            first_page: $result->firstPage(),
            per_page: $result->perPage(),
            to: $result->to(),
            from: $result->from(),
            current_page: $result->currentPage(),
            filter: $this->filter->handle(),
        );
    }
}
