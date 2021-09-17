<?php

namespace Modules\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Nutnet\LaravelSms\Notifications\NutnetSmsChannel;
class SendCode extends Notification
{
    use Queueable;

    public $code;
    public $type;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($code, $type)
    {
        $this->code = $code;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->type === 'email' ? ['mail'] : [NutnetSmsChannel::class];
    }

    public function toNutnetSms($notifiable)
    {
        return "Код подтверждения: $this->code";
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Подтверждение email на mercury.ru')
            ->line('Подтвердите свой email на mercury.ru')
            ->line('Не сообщайте код никому')
            ->line("Код подтверждения: {$this->code}");
    }
}
