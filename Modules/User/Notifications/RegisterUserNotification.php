<?php

namespace Modules\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Nutnet\LaravelSms\Notifications\NutnetSmsChannel;

class RegisterUserNotification extends Notification
{
    use Queueable;

    /** @type string Пароль */
    public $password;

    /** @type string Тип регистрации */
    public $type;

    /**
     * Create a new notification instance.
     *
     * @param string $password
     * @param string $type
     * @return void
     */
    public function __construct(string $password, string $type)
    {
        $this->password = $password;
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
        return "Пароль на сайте mercury.ru: $this->password";
    }

    public function toMail($notifiable) {
        $mail = (new MailMessage)
            ->subject('Регистрация на https://mercury.ru');

        $mail->line('Вы успешно зарегистрированы.');

        if ($notifiable->email) {
            $mail->line('Вы можете войти с помощью email и пароля:');

            $mail->line("Email: $notifiable->email");

            if (!empty($this->password)) {
                $mail->line("Пароль: $this->password");
            }
        }

        $mail->action('Перейти на сайт', url('/'));

        return $mail;
    }
}
