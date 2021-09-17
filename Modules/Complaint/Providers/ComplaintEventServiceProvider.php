<?php


namespace Modules\Order\Providers;


use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Modules\Complaint\Events\ComplaintChangeStatus;
use Modules\Complaint\Events\ComplaintCreated;


class ComplaintEventServiceProvider extends EventServiceProvider
{

    protected $listen = [
        ComplaintCreated::class => [

        ],
        ComplaintChangeStatus::class => [

        ],
    ];
}
