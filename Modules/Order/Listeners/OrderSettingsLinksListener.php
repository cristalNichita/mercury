<?php

namespace Modules\Order\Listeners;

class OrderSettingsLinksListener
{
    public function handle()
    {
        return ['title' => 'Оплата', 'icon' => 'el-icon-s-finance', 'route' => 'orders.payment-settings'];
    }
}
