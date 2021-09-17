<?php

namespace Modules\Order\Events;

use Illuminate\Queue\SerializesModels;

class OrderChangeStatus
{
    use SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }
}
