<?php

namespace App\Models\Traits;

trait UuidTrait
{
    public function getKeyType()
    {
        return "string";
    }

    public function getIncrementing()
    {
        return false;
    }
}
