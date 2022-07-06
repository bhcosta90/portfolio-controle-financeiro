<?php

namespace App\Repository\Presenters;

use Core\Shared\Interfaces\ResultInterface;
use Illuminate\Database\Eloquent\Collection;
use stdClass;

class ResultPresenter implements ResultInterface
{
    protected array $data;

    public function __construct(private Collection $dataItems)
    {
        $this->data = $this->resolveItems(
            items: $dataItems,
        );
    }

    /**
     * @return stdClass[]
     */
    public function items(): array
    {
        return $this->data;
    }

    protected function resolveItems($items)
    {
        $response = [];

        foreach ($items as $item) {
            $stdClass = new stdClass;

            foreach ($item->toArray() as $k => $v) {
                $stdClass->{$k} = $v;
            }

            array_push($response, $stdClass);
        }

        return $response;
    }
}
