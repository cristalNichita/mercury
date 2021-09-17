<?php


namespace Modules\Mailing\Providers;


use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Modules\Mailing\Listeners\MailingEventSubscriber;
use Modules\Mailing\Listeners\MailingSettingsLinksListener;
use Modules\Settings\Events\SettingsLinksEvent;


class MailingEventServiceProvider extends EventServiceProvider
{

    protected $listen = [
        SettingsLinksEvent::class => [
            MailingSettingsLinksListener::class,
        ],
    ];

    protected $subscribe = [
        MailingEventSubscriber::class,
    ];

    /**
     * Определить, должны ли автоматически обнаруживаться события и слушатели.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return true;
    }

    /**
     * Получить каталоги слушателей, которые следует использовать для обнаружения событий.
     *
     * @return array
     */
    protected function discoverEventsWithin()
    {
        return [
            $this->app->path('Listeners'),
        ];
    }
}
