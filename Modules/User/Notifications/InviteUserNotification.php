<?php

namespace Modules\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Integration\Listeners\PushOrderTo1C;
use Nutnet\LaravelSms\Notifications\NutnetSmsChannel;

class InviteUserNotification extends Notification
{
    use Queueable;

    public $inviter_user;
    public $password;


    public function __construct($inviter_user, $password = '')
    {
        $this->inviter_user = $inviter_user;
        $this->password = $password;
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject($this->inviter_user->name . ' пригласил вас на портал https://mercury.ru');

        if ($notifiable->phone) {
            $mail->line('Вы можете войти использую свой телефон: ' . $notifiable->phone);
        }

        if ($notifiable->email) {
            $mail->line(($notifiable->phone ? 'Или' : 'Вы можете войти') . ' с помощью email и пароля:');

            $mail->line("Email: $notifiable->email");
            if (!empty($this->password)) {
                $mail->line("Пароль: $this->password");
            }
        }

        $mail->action('Перейти на сайт', url('/'));

        return $mail;
    }

    public function via()
    {
        return 'mail';
    }

}
