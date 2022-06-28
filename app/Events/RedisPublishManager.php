<?php

namespace App\Events;

use Core\Shared\Abstracts\PublishAbstract;
use Core\Shared\Interfaces\PublishManagerInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class RedisPublishManager implements PublishManagerInterface
{
    /**
     * @param PublishAbstract[] $events
     * @return void
     */
    public function dispatch(array $events): void
    {
        foreach ($events as $rs) {
            Redis::publish($rs->name(), json_encode($rs->publish()));
        }
    }
}
