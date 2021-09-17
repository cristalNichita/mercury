<?php

namespace Modules\Integration\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Integration\Services\OdinService;

class PushCompanyTo1C
{
    public function handle($event)
    {
        OdinService::pushHolding($event->company->holding);
    }
}
