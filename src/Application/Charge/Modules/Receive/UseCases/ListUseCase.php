<?php

namespace Core\Application\Charge\Modules\Receive\UseCases;

use Core\Application\Charge\Modules\Receive\Filter\ReceiveFilter;
use Core\Application\Charge\Modules\Receive\Repository\ReceiveRepository as Repo;
use Core\Shared\UseCases\List\ListInput;
use DateTime;

class ListUseCase
{
    public function __construct(
        private Repo $repository,
        private ReceiveFilter $filter,
    ) {
        //
    }

    public function handle(ListInput $input): DTO\List\Output
    {
        $dateStart = new DateTime($input->filter['date'][0] ?? null);
        $dateFinish = new DateTime($input->filter['date'][1] ?? null);

        if (empty($input->filter['date'][0])) {
            $dateStart->modify('first day of this month');
        }

        if (empty($input->filter['date'][1])) {
            $dateFinish->modify('last day of this month');
        }

        $result = $this->repository;
        $result->filterByDate(
            $dateStart->setTime(0, 0, 0),
            $dateFinish->setTime(23, 59, 59),
            $input->filter['type'] ?? 1
        );
        if (!empty($input->filter['customer_name'])) {
            $result->filterByCustomerName($input->filter['customer_name']);
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
            value: $this->repository->total(filter: $input->filter),
        );
    }
}
