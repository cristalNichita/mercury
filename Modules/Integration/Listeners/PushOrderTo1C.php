<?php

namespace Modules\Integration\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Integration\Services\OdinService;

class PushOrderTo1C
{
    public function handle($event)
    {
        $order = $event->order;
        $order->refresh();

        OdinService::pushOrder($order);
    }
}
