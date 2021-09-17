<?php

namespace Modules\Mailing\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Mailing\Messages\MailingMessage;

/**
 * Единый нотификатор для модуля Mailing
 * @package Modules\Mailing\Notifications
 */
class EventNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Экземпляр отловленного объекта события
     * @var
     */
    public $event;

    /**
     * Тело письма из шаблона рассылки
     * @var
     */
    public $mail_body;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($event, $mail_body)
    {
        $this->event = $event;
        $this->mail_body = $mail_body;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (!empty($this->mail_body)) {
            return (new MailingMessage)
                ->line($this->mail_body);
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
