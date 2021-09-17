<?php

namespace Modules\Order\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Modules\Order\Entities\Cart;
use Modules\Order\Entities\CartItem;
use Modules\Settings\Helpers\SettingsHelper;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AbandonedCartClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:clear
                            {--all : Удалить все корзины}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Удаление брошенных корзин';

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
        $optionAll = $this->option('all') ?? false;

        $carts = null;

        if ($optionAll) {
            $carts = Cart::get()->count();
            try {
                Cart::truncate();
            } catch(\Exception $e) {
                Log::error($e->getMessage());
                $this->error("Произошла ошибка\n");
                $this->error($e->getMessage());
            }
        } else {
            //кол-во часов после которых удаляем брошенные корзины
            $time_clear = (new SettingsHelper())->get('cart__time_clear');
            $date_clear = Carbon::now()->addHours(-1*$time_clear);

            $carts = Cart::select('id')
                            ->where('created_at', '<=', $date_clear)
                            ->get();
            if (count($carts)) {
                $cart_ids = $carts->pluck('id');
                try {
                    Cart::whereIn('id', $cart_ids)->delete();
                    CartItem::whereIn('cart_id', $cart_ids)->delete();
                } catch(\Exception $e) {
                    Log::error($e->getMessage());
                    $this->error("Произошла ошибка\n");
                    $this->error($e->getMessage());
                }
            }
        }

        $cart_count = (is_int($carts) ? $carts : count($carts));

        if (!$cart_count) {
            return;
        }

        $msg = 'Успешно удалены брошенные корзины (' . $cart_count . ')';
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
        return [ ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['all', null, InputOption::VALUE_OPTIONAL, 'Удаление всех корзин', false],
        ];
    }
}
