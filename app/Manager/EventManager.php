<?php

namespace App\Manager;

use Core\Shared\Interfaces\EventManagerInterface;

class EventManager implements EventManagerInterface
{
    public function dispatch(array $events): void
    {
        foreach ($events as $rs) {
            event($rs->name(), $rs->payload());
        }
    }
}
