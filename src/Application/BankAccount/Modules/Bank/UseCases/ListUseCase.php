<?php

namespace Core\Application\BankAccount\Modules\Bank\UseCases;

use Core\Application\BankAccount\Modules\Bank\Filter\BankFilter;
use Core\Application\BankAccount\Modules\Bank\Repository\BankRepository as Repo;
use Core\Shared\UseCases\List\ListInput;

class ListUseCase
{
    public function __construct(
        private Repo $repository,
        private BankFilter $bank,
    ) {
        //
    }

    public function handle(ListInput $input): DTO\List\Output
    {
        $result = $this->repository->paginate(filter: $input->filter, page: $input->page, totalPage: $input->total);
        return new DTO\List\Output(
            items: $result->items(),
            total: $result->total(),
            last_page: $result->lastPage(),
            first_page: $result->firstPage(),
            per_page: $result->perPage(),
            to: $result->to(),
            from: $result->from(),
            current_page: $result->currentPage(),
            filter: $this->bank->handle(),
        );
    }
}
