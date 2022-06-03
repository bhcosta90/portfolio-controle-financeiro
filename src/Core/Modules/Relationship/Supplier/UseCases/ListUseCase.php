<?php

namespace Costa\Modules\Relationship\Supplier\UseCases;

use Costa\Modules\Relationship\Supplier\Repository\SupplierRepositoryInterface;

class ListUseCase
{
    public function __construct(
        protected SupplierRepositoryInterface $relationship,
    ) {
        //
    }

    public function exec(DTO\List\Input $input): DTO\List\Output
    {
        $result = $this->relationship->paginate(
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
