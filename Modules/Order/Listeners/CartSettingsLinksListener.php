<?php

namespace Modules\Order\Listeners;

class CartSettingsLinksListener
{
    public function handle()
    {
        return ['title' => 'Корзина', 'icon' => 'el-icon-shopping-cart-1', 'route' => 'carts.settings'];
    }
}
