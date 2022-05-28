<?php

namespace Costa\Modules\Recurrence\UseCases;

use Costa\Modules\Recurrence\Repository\RecurrenceRepositoryInterface;

class ListUseCase
{
    public function __construct(
        protected RecurrenceRepositoryInterface $relationship,
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
