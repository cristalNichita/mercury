<?php

namespace Modules\Integration\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Modules\Integration\Services\OdinService;

class PushContactTo1C
{
    public function handle($event)
    {
        OdinService::pushHolding($event->contact->holding);
    }
}
