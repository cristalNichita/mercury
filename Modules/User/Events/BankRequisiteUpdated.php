<?php

namespace Modules\User\Events;

use Illuminate\Queue\SerializesModels;

class BankRequisiteUpdated
{
    use SerializesModels;

    public $company;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($company)
    {
        $this->company = $company;
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
