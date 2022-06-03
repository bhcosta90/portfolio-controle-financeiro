<?php

namespace Costa\Shared\ValueObject;

class DeleteObject
{
    public function __construct(public bool $success)
    {
        //
    }
}
