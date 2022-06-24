<?php

namespace Core\Financial\Recurrence\UseCases;

use Core\Financial\Recurrence\Repository\RecurrenceRepositoryInterface;
use Core\Shared\UseCases\List\{ListInput, ListOutput};

class ListUseCase
{
    public function __construct(
        private RecurrenceRepositoryInterface $repo
    ) {
        //
    }

    public function handle(ListInput $input): ListOutput
    {
        $result = $this->repo->paginate(
            $input->filter,
            $input->page,
            $input->total
        );

        return new ListOutput(
            items: array_map(fn ($rs) => $this->repo->entity($rs), $result->items()),
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
