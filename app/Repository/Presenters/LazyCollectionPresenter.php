<?php

namespace App\Repository\Presenters;

use Core\Shared\Interfaces\ResultInterface;
use Illuminate\Support\LazyCollection;
use stdClass;

class LazyCollectionPresenter implements ResultInterface
{
    protected array $data;

    public function __construct(private LazyCollection $paginator)
    {
        $this->data = $this->resolveItems(
            items: $this->paginator
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
