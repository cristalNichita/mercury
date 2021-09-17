<?php

namespace Modules\Mailing\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Modules\Mailing\Entities\Mailing;
use Modules\Mailing\Helpers\MailingHelper;
use Modules\Mailing\Notifications\EventNotification;

/**
 * Подписчик на события для отправки уведомлений по шаблону
 * @package Modules\Mailing\Listeners
 */
class MailingEventSubscriber implements ShouldQueue
{
    protected $config;
    protected $events;
    protected $hendle_event;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->config = Config::get('mailing');
        if (Schema::hasTable('mailings')) {
            $this->events = Mailing::with('event', 'status', 'event.statuses')->get()->toArray();
        }

    }

    /**
     * Единый обработчик событий для Модуля Mailing
     * @param $event
     */
    public function handle($event)
    {
        $this->setHandleEvent($event);
        // TODO могут быть несколько событий одного типа но с разными статусами
        // TODO нужно каким то образом связать статусы событий модуля Mailing со статусами Complate, Order и другими
        $current_event = $this->getCurrentEvent($this->hendle_event);

        if ($current_event->count()) {
            // TODO необходимо добавить получателя в Шаблон рассылки или сделать единого для всех шаблонов
            Notification::route('mail', 'grebenuko@echo-company.ru')
                ->notify(new EventNotification($event, $current_event->first()['mail_template']));
        }

    }

    /**
     * Регистрация события для прослушивания
     * @param $events
     * @return array
     */
    public function subscribe($events)
    {
        return $this->getSubscribe();
//            [
//            \Illuminate\Auth\Events\Registered::class => [
//                [MailingEventSubscriber::class, 'handleUserRegister']
//            ],
//            \Modules\Complaint\Events\ComplaintChangeStatus::class => [
//                [MailingEventSubscriber::class, 'handleComplaintChangeStatus']
//            ]
//        ];

    }

    /**
     * Формирования массива событий для регистрации их прослушивания
     * @return array
     */
    protected function getSubscribe () {
        $result = [];
        $subscribe = collect($this->config['subscribe'])
                        ->filter()
                        ->toArray();
        foreach ($subscribe as $item) {
            $result[array_key_first($item)][] = current($item);
        }
        return $result;
    }

    protected function setHandleEvent ($event) {
        $this->hendle_event = get_class($event);
    }

    protected function getCurrentEvent ($event_name) {
        return collect($this->events)->filter(function($item) use ($event_name){
            if (isset($item['event']['handling']['handle'])) {
                return array_key_first($item['event']['handling']['handle']) == $event_name;
            }

            return false;

        });
    }
}
