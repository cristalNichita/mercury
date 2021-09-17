<?php

namespace Modules\Integration\Console;

use Illuminate\Console\Command;
use Modules\Integration\Services\OdinService;
use Modules\Integration\Traits\ParserCommandTrait;
use Modules\Order\Entities\Order;
use Modules\User\Entities\Holding;

/**
 * Class OdinOrdersCommand
 * Экспорт заказовв
 * @package Modules\Integration\Console
 */
class OdinOrdersCommand extends Command
{

    use ParserCommandTrait;

    protected $signature = 'odin:orders';
    protected $description = 'Полный экспорт заказов';

    public function handle()
    {
        $this->info($this->description);
        $this->info('--------------------------------');
        $this->newLine();

        $xml = OdinService::createXml('Заказы');

        $orders = Order::with(['contact.params', 'company.params', 'items.product'])->get();
        foreach ($orders as $order) {
            $this->line($order->code || '');
            OdinService::addOrder($xml, $order);
        }

        OdinService::save($xml, 'orders', 'full');

        $this->info('Экспорт успешно завершен');
    }
}
