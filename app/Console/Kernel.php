<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Получаем товары из 1С
        $schedule->command('parser:catalog')
            ->everyFiveMinutes()
            ->withoutOverlapping();

        // Контрагенты из 1С
        $schedule->command('parser:users')
            ->everyFiveMinutes()
            ->withoutOverlapping();

        // Полный экспорт контрагентов
        $schedule->command('odin:users')
            ->dailyAt('00:00');

        // Проверка баланса sms
        $schedule->command('sms:balance-check')
            ->dailyAt('06:00');

        // Удаление брошенных корзин, которые были созданы более
        // значения часов указаного в настройке cart__time_clear
        $schedule->command('cart:clear')
            ->everyFiveMinutes();

        // Уведомления пользователей о брошенной корзине, которые были созданы более
        // значения часов указаного в настройке cart__time_notify
        $schedule->command('cart:notify')
            ->dailyAt('10:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
