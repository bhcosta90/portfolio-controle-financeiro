<?php

namespace Costa\Modules\Charge\UseCases\Recurrence;

use Costa\Modules\Charge\Repository\RecurrenceRepositoryInterface;

class RecurrenceListUseCase
{
    public function __construct(private RecurrenceRepositoryInterface $repo)
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
