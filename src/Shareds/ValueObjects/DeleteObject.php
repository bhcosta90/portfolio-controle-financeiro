<?php

namespace Costa\Shareds\ValueObjects;

class DeleteObject
{
    public function __construct(public bool $success)
    {
        //
    }
}
