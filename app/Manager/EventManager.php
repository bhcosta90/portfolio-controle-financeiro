<?php

namespace App\Manager;

use Core\Shared\Interfaces\EventManagerInterface;

class EventManager implements EventManagerInterface
{
    public function dispatch(array $events): void
    {
        foreach ($events as $event) {
            event($event);
        }
    }
}
