<?php

namespace Core\Application\Payment\Services;

use Core\Application\Payment\Repository\PaymentRepository as Repo;
use Core\Shared\UseCases\List\{ListInput, ListOutput};

class ListService
{
    public function __construct(
        private Repo $repository
    ) {
        //
    }

    public function handle(ListInput $input): ListOutput
    {
        $result = $this->repository->paginate(filter: $input->filter, page: $input->page, totalPage: $input->total);
        return new ListOutput(
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
