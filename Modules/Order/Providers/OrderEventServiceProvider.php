<?php


namespace Modules\Order\Providers;


use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Modules\Order\Listeners\CartSettingsLinksListener;
use Modules\Order\Listeners\OrderSettingsLinksListener;
use Modules\Settings\Events\SettingsLinksEvent;


class OrderEventServiceProvider extends EventServiceProvider
{

    protected $listen = [
        SettingsLinksEvent::class => [
            OrderSettingsLinksListener::class,
            CartSettingsLinksListener::class
        ],
    ];
}
