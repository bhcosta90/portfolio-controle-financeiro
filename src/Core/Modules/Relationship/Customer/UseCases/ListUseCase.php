<?php

namespace Costa\Modules\Relationship\Customer\UseCases;

use Costa\Modules\Relationship\Customer\Repository\CustomerRepositoryInterface;

class ListUseCase
{
    public function __construct(
        protected CustomerRepositoryInterface $repo,
    ) {
        //
    }

    public function exec(DTO\List\Input $input): DTO\List\Output
    {
        $result = $this->repo->paginate(
            $input->filter,
            $input->page,
            $input->total
        );

        return new DTO\List\Output(
            items: $result->items(),
            total: $result->total(),
            last_page: $result->lastPage(),
            first_page: $result->firstPage(),
            per_page: $result->perPage(),
            to: $result->to(),
            from: $result->from(),
            current_page: $result->currentPage(),
        );
    }
}
