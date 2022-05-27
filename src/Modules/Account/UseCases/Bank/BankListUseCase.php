<?php

namespace Costa\Modules\Account\UseCases\Bank;

use Costa\Modules\Account\Repository\BankRepositoryInterface;

class BankListUseCase
{
    public function __construct(private BankRepositoryInterface $repo)
    {
        //
    }

    public function exec(DTO\List\Input $input): DTO\List\Output
    {
        $result = $this->repo->paginate(
            $input->filter,
            $input->order,
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
