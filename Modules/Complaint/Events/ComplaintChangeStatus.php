<?php

namespace Modules\Complaint\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Complaint\Entities\Complaint;

class ComplaintChangeStatus
{
    use SerializesModels;

    public $complaint;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
