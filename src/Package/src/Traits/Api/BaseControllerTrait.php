<?php

namespace Costa\Package\Traits\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ReflectionClass;

trait BaseControllerTrait
{
    protected abstract function service(): string;

    protected abstract function ruleStore(): array;

    protected abstract function ruleUpdate(): array;

    protected abstract function resource(): string;

    protected $paginateSize = 15;

    public function index(Request $request)
    {
        $service = $this->getService();
        $serviceData = $service->getDataIndex($request->all());

        $data = !$this->paginateSize ? $serviceData->all() : $serviceData->paginate($this->paginateSize);

        $resourceCollection = $this->resourceCollection();

        if (is_null($resourceCollection)) {
            $resource = $this->resource();
            return $resource::collection($data);
        }

        $refClass = new ReflectionClass($resourceCollection);
        $isCollectionClass = $refClass->isSubclassOf(ResourceCollection::class);

        return $isCollectionClass ? new $resourceCollection($data) : $resourceCollection::collection($data);
    }

    public function show(Request $request, $id)
    {
        $service = $this->getService();
        if (!method_exists($service, 'getBy')) {
            throw new Exception('method `getBy` do not implemented in service', Response::HTTP_BAD_REQUEST);
        }

        $sendData = $request->all();
        if ($user = auth()->user()) {
            $sendData += ['user_id' => $user->id];
        }
        $serviceData = $service->getBy($id, $sendData);

        $resource = $this->resource();
        return new $resource($serviceData);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->ruleStore());
        $this->setUserInData($data);

        $service = $this->getService();
        if (!method_exists($service, 'actionStore')) {
            throw new Exception('method `actionStore` do not implemented in service', Response::HTTP_BAD_REQUEST);
        }

        $serviceData = $service->actionStore($data);
        $resource = $this->resource();

        return $serviceData instanceof \Illuminate\Support\Collection
            ? $resource::collection($serviceData)->response()->setStatusCode(Response::HTTP_CREATED)
            : new $resource($serviceData);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate($this->ruleUpdate());
        $this->setUserInData($data);

        $service = $this->getService();

        if (!method_exists($service, 'actionUpdate')) {
            throw new Exception('method `actionUpdate` do not implemented in service', Response::HTTP_BAD_REQUEST);
        }

        $serviceData = $service->actionUpdate($id, $data);
        $resource = $this->resource();
        return new $resource($serviceData);
    }

    public function destroy(Request $request, $id)
    {
        $service = $this->getService();

        if (!method_exists($service, 'deleteBy')) {
            throw new Exception('method `deleteBy` do not implemented in service', Response::HTTP_BAD_REQUEST);
        }

        $service->deleteBy($id, $request->all());
        return response()->noContent();
    }

    protected function setUserInData(&$sendData)
    {
        if ($user = auth()->user()) {
            $sendData += ['user_id' => $user->id];
        }
    }

    protected function getService()
    {
        return app($this->service());
    }

    protected function resourceCollection(): string|null
    {
        return null;
    }
}
