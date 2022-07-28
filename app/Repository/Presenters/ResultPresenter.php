<?php

namespace App\Repository\Presenters;

use Core\Shared\Interfaces\ResultInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;
use stdClass;

class ResultPresenter implements ResultInterface
{
    protected array $data;

    public function __construct(private Collection|LazyCollection $dataItems)
    {
        $this->data = $this->resolveItems(
            items: $dataItems,
        );
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

    /**
     * @return stdClass[]
     */
    public function items(): array
    {
        return $this->data;
    }
}
