<?php

namespace Modules\Order\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Modules\Integration\Entities\User;
use Modules\Order\Entities\Cart;
use Modules\Order\Notifications\AbandonedCartNotification;
use Modules\Order\Notifications\AbandonedCartUserNotification;
use Modules\Settings\Helpers\SettingsHelper;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AbandonedCartNotifyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Уведомление о брошенных корзинах';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $carts = null;

        $manager_id_notify = (new SettingsHelper())->get('cart__manager_id_notify');
        $manager = null;
        if($manager_id_notify) {
            $manager = User::find($manager_id_notify);
        }


        //кол-во часов после которых отправляется уведомление о брошенной корзине
        $time_notify = (new SettingsHelper())->get('cart__time_notify');
        $date_notify = Carbon::now()->addHours(-1*$time_notify);

        $this->line('Текущая дата: ' . Carbon::now()->format("d.m.Y H:i"));
        $this->line('Дата уведомлений: ' . $date_notify->format("d.m.Y H:i"));

        $carts = Cart::where('created_at', '<=', $date_notify)
                        ->whereNotNull('user_id')
                        ->get();
        $this->line('Кол-во брошенных корзин: ' . count($carts));

        if (!count($carts)) {
            return;
        }

        foreach ($carts as $cart) {
            $cart->notify(new AbandonedCartUserNotification());
            if (!$manager && !$manager->email ?? null){
                Notification::route('mail', $manager->email)
                    ->notify(new AbandonedCartNotification($cart));
            } else {
                $msg = 'В настройках не указан менеджер, для получения уведомлений';
                Log::warning($msg);
                $this->warn($msg);
            }
        }

        $msg = 'Уведомления отправленны для брошенных корзин (' . count($carts) . ')';
        Log::info($msg);
        $this->info($msg);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
