<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CostResource extends JsonResource
{
    public function toArray($request)
    {
        return new ChargeResource($this->charge);
    }
}
