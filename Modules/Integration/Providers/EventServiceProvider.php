<?php


namespace Modules\Integration\Providers;


use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Complaint\Events\ComplaintCreated;
use Modules\Integration\Listeners\PushCompanyTo1C;
use Modules\Integration\Listeners\PushComplaintTo1C;
use Modules\Integration\Listeners\PushContactTo1C;
use Modules\Integration\Listeners\PushOrderTo1C;
use Modules\Order\Events\OrderChangeStatus;
use Modules\Order\Events\OrderCreated;
use Modules\User\Events\BankRequisiteCreate;
use Modules\User\Events\BankRequisiteUpdated;
use Modules\User\Events\CompanyCreated;
use Modules\User\Events\CompanyUpdated;
use Modules\User\Events\ContactCreated;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ContactCreated::class => [
            PushContactTo1C::class,
        ],
        CompanyCreated::class => [
            PushCompanyTo1C::class,
        ],
        CompanyUpdated::class => [
            PushCompanyTo1C::class,
        ],
        BankRequisiteCreate::class => [
            PushCompanyTo1C::class,
        ],
        BankRequisiteUpdated::class => [
            PushCompanyTo1C::class,
        ],
        ComplaintCreated::class => [
            PushComplaintTo1C::class,
        ],
        OrderCreated::class => [
            PushOrderTo1C::class,
        ],
        OrderChangeStatus::class => [
            PushOrderTo1C::class,
        ]
    ];
}
