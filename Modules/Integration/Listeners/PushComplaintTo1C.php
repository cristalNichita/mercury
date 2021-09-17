<?php

namespace Modules\Integration\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Complaint\Entities\Complaint;
use Modules\Integration\Services\OdinService;

class PushComplaintTo1C
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $complaint = Complaint::find($event->complaint->id);

        OdinService::pushComplaint($complaint);

    }
}
